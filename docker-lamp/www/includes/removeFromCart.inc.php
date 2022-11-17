<?php

require_once "../connection/mysqli_conn.php";
session_start();

if(isset($_GET['remove'])){
    $itemID = $_GET['remove'];

    $stmt =$conn->prepare("DELETE FROM cart WHERE itemID =? AND customerEmail = ?");
    $stmt->bind_param("is",$itemID, $_SESSION['Email']);
    $stmt->execute();

    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'Item removed!';
    header('location: ../cart.php');
}


if(isset($_GET['clear'])){
    $stmt = $conn->prepare("DELETE FROM cart WHERE customerEmail=?");
    $stmt->bind_param("s", $_SESSION['Email']);
    $stmt->execute();
    $_SESSION['showAlert'] = 'block';
    $_SESSION['message'] = 'All item removed!';
    header("location: ../cart.php");
}

?>