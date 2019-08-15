<?php
require('vendor/autoload.php');
use aitsydney\Search;

if( isset($_GET['query'])){
    $search = new Search();
    $result = $search -> getSearchResult();
}

?>