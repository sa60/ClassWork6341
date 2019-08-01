<?php
namespace aitsydney;
use aitsydney\Database;
class Product extends Database{
    public $products = array();
    public function __construct(){
        parent::__construct();
    }
    public function getProducts(){
        $query = "SELECT 
        @product_id := product.product_id AS product_id,
        product.name,
        product.description,
        product.price,
        ( SELECT @image_id := product_image.image_id FROM product_image WHERE product_image.product_id = @product_id LIMIT 1 ) AS image_id,
        ( SELECT image_file_name FROM image WHERE image.image_id = @image_id ) AS image
        FROM product";

        $statement = $this -> connection -> prepare( $query );
        if( $statement -> execute() ){
            $result = $statement -> get_result();
            while( $row = $result -> fetch_assoc() ){
                array_push( $this -> products, $row );
            }
        }
        return $this -> products;
    }
    //get product by id
    public function getProductDetail( $id ){
        $query = "SELECT
        product_id,
        name,
        description,
        price
        FROM product
        Where product_id = ? ";

        $statement = $this -> connection -> prepare( $query );
        $statement -> bind_param('i', $id );
        if( $statement -> excecute()){
            $product_detail = array();
            $result = $statement -> get_result();
            $row = $result -> fetch_assoc();
            $product_detail['product'] = $row;
            return $product_detail;        
        }

    }
}
?>