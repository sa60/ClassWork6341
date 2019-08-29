<?php
namespace aitsydney;

use aitsydney\Database;

class Account extends Database{
    public function __construct(){
        parent::__construct();
    }

    public function register( $email, $password ){
        $register_errors = array();
        $register_response = array();

        if( filter_var($email, FILTER_VALIDATE_EMAIL) == false ){
            $register_errors['email']='invalid email address';
        }
        if( strlen($password) < 8 ){
            $register_errors['password'] = 'minimum 8 characters';
        }
        
        if( count($register_errors) == 0){
            //hash the password
            $hash = password_hash( $password, PASSWORD_DEFAULT );
            //generate account id
            $id = $this -> createAccountId();
            //query to insert into database
            $query = "
                INSERT INTO account (account_id,email,password,created,accessed,updated)
                VALUES( UNHEX(?), ?, ?, NOW(), NOW(), NOW() )
            ";
            try{
                $statement = $this->connection->prepare($query);
                if( $this->connection->prepare($query) == false ){
                    throw(new Exception('query failed') );
                }
                $statement -> bind_param('sss', $id, $email, $hash );
                if( $statement -> execute() == false){
                    throw( new Exception('execute failed') );
                }
                else{
                    //account is created
                    $register_response['success'] = true;
                    $this -> setUserSession( $id );
                }
            }
            catch( Exception $exe ){
                echo $exc -> getMessage();
                error_log( $exc -> getMessage() );
            }
        }
        else{
            //return error messages
            $register_response['errors'] = $register_errors;
            $register_response['success'] = false;
        }
        return $register_response;
    }

    public function createAccountId(){
        if( function_exists('random_bytes')){
            $bytes = random_bytes(16);
        }
        else{
            $bytes = openssl_random_pseudo_bytes(16);
        }
        return bin2hex($bytes);
    }

    private function setUserSession( $account_id ){
        if( session_status() == PHP_SESSION_NONE ){
            session_start();
        }
        $_SESSION['auth'] = $account_id;
    }

    public function logout(){
        if( session_status() == PHP_SESSION_NONE ){
            session_start();
        }
        unset( $_SESSION['auth'] );
    } 

    public function login( $email, $password ){
        $response = array();
        $errors = array();
        $query = "
            SELECT HEX( account_id ) as account_id,email,password
            FROM account
            WHERE email = ?        
        ";
        try{
            $statement = $this -> connection -> prepare( $query );
            if( $statement == false ){
                throw new \Exception('query error');
            }
            if( $statement -> bind_param('s', $email ) == false ){
                throw new \Exception('parameter error');
            }
            if( $statement -> execute() == false ){
                throw new \Exception('execution error');
            }
            else{
                // query runs successfully
                $result = $statement -> get_result();
                $account = $result -> fetch_assoc();
                if( $result -> num_rows == 0){
                    //account does not exist in database
                    $errors['account'] = 'account not found';
                }
                else{
                    // see if passwords match
                    if( password_verify($password,$account['password']) == false){
                        //passwords dont match
                        $errors['account'] = 'credentials do not match out records';
                    }
                }
            }
        }
        catch( Exception $exc ){
            error_log( $exc -> getMessage());
        }
        // check if there are errors
        if( count($errors) > 0 ){
            $response['success'] = false;
            $response['errors'] = $errors;
        }
        else{
            $response['success'] = true;
            $this -> setUserSession( $account['account_id']);
        }
        return $response;
    }
}
?>