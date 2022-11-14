<?php

require_once "../connection/mysqli_conn.php";
session_start();

if(isset($_GET['remove'])){
    $itemID = $_GET['remove'];

    $stmt =$conn->prepare("DELETE FROM cart WHERE itemID =?");
    $stmt->bind_param("i",$itemID);
    $stmt->execute();

    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'Item removed!';
    header('location: ../cart.php');
}


if(isset($_GET['clear'])){
    $stmt = $conn->prepare("DELETE FROM cart");
    $stmt->execute();
    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'All item removed!';
    header("location: ../cart.php");
}

?>