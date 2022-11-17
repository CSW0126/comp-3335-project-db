<?php
require_once "../connection/mysqli_conn_update_cart.php";
session_start();
if(isset($_POST['qty'])){
    $qty= $_POST['qty'];
    $itemID= $_POST['itemID'];
    $price= $_POST['price'];
    $rQ = $_POST['rQ'];

    if($qty > $rQ){
        $qty = $rQ;
    }

    // connect to database to check item price is change or not

    $totalPrice = $qty * $price;

    $stmt = $conn->prepare("UPDATE cart SET qty=?, total_price=? WHERE itemID=? AND customerEmail=?");
    $stmt->bind_param("isis",$qty,$totalPrice,$itemID, $_SESSION['Email']);
    $stmt->execute();
}


?>