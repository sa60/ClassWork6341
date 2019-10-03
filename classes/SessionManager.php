<?php
namespace aitsydney;

class SessionManager{
  public static function start(){
    try{
      if( session_status() == PHP_SESSION_NONE ){
        session_start();
      }
    }
  }
  public static function set( $name, $value ){
    self::start();
    
  }
}
?>