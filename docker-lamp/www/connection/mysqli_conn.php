<?php
$hostname="db";
$username = "user";
$pwd="test";
$db = "myDb";
$conn=mysqli_connect($hostname,$username,$pwd,$db) ;

if(!$conn){
    die(mysqli_connect_error());
}

mysqli_set_charset($conn,"utf8");

?>