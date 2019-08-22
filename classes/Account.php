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
                VALUES( ?, ?, ?, NOW(), NOW(), NOW() )
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
            $bytes = random_bytes(8);
        }
        else{
            $bytes = openssl_random_pseudo_bytes(8);
        }
        return bin2hex($bytes);
    }

    public function login( $email, $password ){

    }
}
?>