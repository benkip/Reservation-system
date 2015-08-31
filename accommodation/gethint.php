<?php
require_once('lib.php');
// get the q parameter from URL
$q = $_REQUEST["q"];
$query="select price from items where item_id=$q";
$result=getData($query);
echo $result[0]['price'];
?> 