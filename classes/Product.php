<?php
namespace aitsydney;
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
}
?>