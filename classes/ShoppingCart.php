<?php

namespace aitsydney;

use aitsydney\Database;
use \Exception;

class ShoppingCart extends Database{
  private $cart_id;
  private $account_id;

  public function __construct(){
    parent::__construct();
  }

  public function createCart( $account_id ){
    $cart_query = "
    INSERT INTO shopping_cart
    ";
  }
  public function findCart(){

  }
  public function addItem( $product_id, $quantity = 1 ){
    
  }
}
?>