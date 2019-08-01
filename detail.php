<?php
require('vendor/autoload.php');

use aitsydney\Product;

//get the product id from request
if( isset($_GET['product_id'])){
    echo 'product id = ' . $_GET['product_id'];
}
?>