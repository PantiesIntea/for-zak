<?php
    $link = mysqli_connect("localhost", "root", "root", "someshop");

$asd = "SELECT charvalue FROM charprod WHERE product = 6";
$zxc = $link->query($asd);
$row = mysqli_fetch_all($zxc, MYSQLI_ASSOC);
$row = array_map(function($item) { 
    return $item['charvalue'];
 },$row);
print_r($row);
