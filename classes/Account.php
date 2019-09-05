<?php
namespace aitsydney;

use aitsydney\Database;

class Account extends Database{

  public function __construct(){
    parent::__construct();
  }

  public function register( $email, $password ){
    $query = "
      INSERT INTO account ( account_id, email, password, created, accessed, updated )
      VALUES ( UNHEX(?), ?, ?, NOW(), NOW(), NOW() )
    ";

    $register_errors = array();
    $response = array();

    if( strlen($password) < 8 ){
      $register_errors['password'] = "minimum 8 characters";
    }

    if( filter_var($email, FILTER_VALIDATE_EMAIL ) == false ){
      $register_errors['email'] = "email address not valid";
    }
    //if there are no errors with email and password
    if( count( $register_errors ) == 0 ){
      //hash the password
      $hash = password_hash( $password, PASSWORD_DEFAULT );
      $account_id = $this -> createAccountId();
      
      try{
        $statement = $this -> connection -> prepare( $query );
        if( $statement == false ){
          throw( new \Exception('query error') );
        }
        
        if( $statement -> bind_param('sss', $account_id , $email, $hash ) == false ){
          throw( new \Exception('cannot bind parameters') );
        }
        if( $statement -> execute() == false ){
          throw( new \Exception('failed to execute') );
        }
        else{
          // account is created in database
          
          $response['success'] = true;
        }
      }
      catch( Exception $exc ){
        error_log( $exc -> getMessage() );
      }
    }
    else{
      $response['errors'] = $register_errors;
      $response['success'] = false;
    }
    return $response;
  }
  private function createAccountId(){
    //get random bytes
    $bytes = openssl_random_pseudo_bytes(16);
    //convert to hexadecimal
    $str = bin2hex( $bytes );
    return $str;
  }
}
?>