<?php
require('vendor/autoload.php');

use aitsydney\WishList;
$wish_list = new WishList();
$wish_total = $wish_list -> getWishListTotal();

use aitsydney\Navigation;

$nav = new Navigation();
$nav_items = $nav -> getNavigation();

use aitsydney\Product;

$products = new Product();
$products_result = $products -> getProducts();

use aitsydney\Category;

$cat = new Category();
$categories = $cat -> getCategories();

//create twig loader
//$loader = new \Twig\Loader\FilesystemLoader('templates');
$loader = new Twig_Loader_Filesystem('templates');

//create twig environment
$twig = new Twig_Environment($loader);

//load a twig template
$template = $twig -> load('home.twig');

//pass values to twig
echo $template -> render([
    'wish_count' => $wish_total,
    'categories' => $categories,
    'navigation' => $nav_items,
    'products' => $products_result,
    'title' => 'Hello shop'
]);
?>