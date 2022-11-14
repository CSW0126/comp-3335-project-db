<?php
require_once "../connection/mysqli_conn.php";

if(isset($_POST['qty'])){
    $qty= $_POST['qty'];
    $itemID= $_POST['itemID'];
    $price= $_POST['price'];
    $rQ = $_POST['rQ'];

    if($qty > $rQ){
        $qty = $rQ;
    }

    $totalPrice = $qty * $price;

    $stmt = $conn->prepare("UPDATE cart SET qty=?, total_price=? WHERE itemID=?");
    $stmt->bind_param("isi",$qty,$totalPrice,$itemID);
    $stmt->execute();
}


?>