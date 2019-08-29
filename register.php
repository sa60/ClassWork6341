<?php
require('vendor/autoload.php');

use aitsydney\Navigation;
use aitsydney\Account;


$nav = new Navigation();
$navigation = $nav -> getNavigation();

if( $_SERVER['REQUEST_METHOD']=='POST' ){
    $email = $_POST['email'];
    $password = $_POST['password'];

    //create an instance of Account class
    $acc = new Account();
    $register = $acc -> register( $email, $password );
}
else{
    $register='';
}

//create twig loader for templates
$loader = new Twig_Loader_Filesystem('templates');
//create twig environment and pass the loader
$twig = new Twig_Environment($loader);
//call a twig template
$template = $twig -> load('register.twig');
//output the template and pass the data

echo $template -> render( array(
    'register' => $register,
    'navigation' => $navigation,
    'title' => 'Register for an account'
) );
?>