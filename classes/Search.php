<?php
namespace aitsydney;

use aitsydney\Database;

class Search extends Database{
    public $search_result = array();
    public $search_query = null;

    public function __construct(){
        parent::__construct();
        if( isset($_GET['query']) ){
            $this -> search_query = $_GET['query'];
        }
    }

    public function getSearchResult(){
        if( isset($this -> search_query) == false ){
            return;
        }
        $search_param = "%" . $this -> search_query . "%";
        $query = "
            SELECT
            product_id,
            name,
            price,
            description
            FROM product
            WHERE
            name LIKE ?
            OR
            description LIKE ?
        ";
        $statement = $this -> connection -> prepare( $query );
        $statement -> bind_param('ss', $search_param, $search_param );
        try{
            if( $statement -> execute() == false ){
                throw( new Exception('search error') );
            }
            else{
                $result = $statement -> get_result();
                $items = array();
                while( $row = $result -> fetch_assoc() ){
                    array_push( $items, $row );
                }
                $this -> search_result['items'] = $items;
                $this -> search_result['query'] = $this -> search_query;
                return $this -> search_result; 
            }
        }
        catch( Exception $exc ){
            echo $exc -> getMessage();
        }
    }
}
?>