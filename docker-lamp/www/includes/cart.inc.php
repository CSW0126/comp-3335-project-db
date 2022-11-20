<?php
session_start();
if ($_SESSION['role'] != "customer") {
    header("Location: ../index.php");
    exit();
}
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "customer") {
        require_once '../connection/mysqli_conn_customer_user.php';
    }
}
if(isset($_POST['qty'])){
    $itemID= openssl_decrypt(base64_decode($_POST['itemID']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
    $qty= openssl_decrypt(base64_decode($_POST['qty']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
    $stmt = $conn->prepare("SELECT * FROM goods WHERE goodsNumber=?");
    $stmt->bind_param("s", $itemID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = mysqli_fetch_assoc($result)) {
        $price=$row['stockPrice'];
        $rQ = $row['remainingStock'];
        if($qty > $rQ){
            $qty = $rQ;
        }
        $totalPrice = $qty * $price;

        $stmt1 = $conn->prepare("UPDATE cart SET name=?, qty=?, consignmentStoreID=?, total_price=?, price=?, rQ=? WHERE itemID=? AND customerEmail=?");
        $stmt1->bind_param("siiiiiss",$row['goodsName'], $qty, $row['consignmentStoreID'], $totalPrice, $price, $rQ,$itemID, $_SESSION['Email']);
        $stmt1->execute();
    }

}


?>