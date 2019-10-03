<?php
require('vendor/autoload.php');

use aitsydney\WishList;
$wish_list = new WishList();
if( $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['add']) ){
    //check if add == list
    if( $_GET['add'] == 'list' ){
        $wish_list -> addItem( $_GET['product_id'] );
    }
}
$wish_total = $wish_list -> getWishListTotal();

use aitsydney\Navigation;

$nav = new Navigation();
$nav_items = $nav -> getNavigation();

use aitsydney\ProductDetail;

//get the product id from url parameter
if( isset( $_GET['product_id'] ) == false ){
    echo "no parameter set";
    exit();
}

//create an instance of ProductDetail class
$pd = new ProductDetail();
$detail = $pd -> getProductDetail( $_GET['product_id'] );

//initialise twig template
$loader = new Twig_Loader_Filesystem('templates');

//create twig environment
$twig = new Twig_Environment($loader);

//load a twig template
$template = $twig -> load('detail.twig');

//pass values to twig
echo $template -> render([
    'wish_count' => $wish_total,
    'navigation' => $nav_items,
    'detail' => $detail,
    'title' => $detail['product']['name']
]);
?>