<?php
require('vendor/autoload.php');

use aitsydney\Product;

//create an instance of Product class
$p = new Product();
$products = $p -> getProducts();

//print_r($products);
//create twig loader for templates
$loader = new Twig_Loader_Filesystem('templates');
//create twig environment and pass the loader
$twig = new Twig_Environment($loader);
//call a twig template
$template = $twig -> load('home.twig');
//output the template and pass the data

echo $template -> render( array(
    'products' => $products,
    'title' => 'Welcome to the shop'
) );
?>