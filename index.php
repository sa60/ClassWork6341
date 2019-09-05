<?php
require('vendor/autoload.php');

use aitsydney\Navigation;

$nav = new Navigation();
$navigation = $nav -> getNavigation();

use aitsydney\Product;

//create an instance of Product class
$p = new Product();
$products = $p -> getProducts();

//create categories
use aitsydney\Category;

$cat = new Category();
$categories = $cat -> getCategories();

//print_r($products);
//create twig loader for templates
$loader = new Twig_Loader_Filesystem('templates');
//create twig environment and pass the loader
$twig = new Twig_Environment($loader);
//call a twig template
$template = $twig -> load('home.twig');
//output the template and pass the data

echo $template -> render( array(
    'categories' => $categories,
    'navigation' => $navigation,
    'products' => $products,
    'title' => 'Welcome to the shop'
) );
?>