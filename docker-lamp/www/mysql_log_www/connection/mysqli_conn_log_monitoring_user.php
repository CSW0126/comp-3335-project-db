<?php

session_start();
$hostname="db";
$username = $_SESSION['adminID'];
$pwd=$_SESSION['pwd'];
$db = "mysql";
$conn=mysqli_connect($hostname,$username,$pwd,$db) ;

if(!$conn){
    die(mysqli_connect_error());
}

mysqli_set_charset($conn,"utf8");

?>