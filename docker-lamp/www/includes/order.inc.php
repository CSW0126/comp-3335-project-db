<?php
session_start();
if ($_SESSION['role'] != "customer") {
    header("Location: ../index.php");
    exit();
}
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "customer") {
        require '../connection/mysqli_conn_customer_user.php';
    }
}
if (isset($_POST['itemID'])) {
    $itemID = openssl_decrypt(base64_decode($_POST['itemID']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
    $stmtGoods = $conn->prepare("SELECT * FROM goods WHERE goodsNumber=?");

    $stmtGoods->bind_param("s", $itemID);
    $stmtGoods->execute();
    $goodsResult = $stmtGoods->get_result();
    if ($row1 = mysqli_fetch_assoc($goodsResult)) {
        $itemName = $row1['goodsName'];
        $itemPrice = $row1['stockPrice'];
        $consignmentStoreID = $row1['consignmentStoreID'];
        $rQ = $row1['remainingStock'];
    } else {
        header("Location: ../makeOrder.php?msg=addfail");
        exit();
    }
    $qty = 1;

    $stmt = $conn->prepare("SELECT itemID FROM cart WHERE itemID = ? AND customerEmail=?");

    $stmt->bind_param("ss", $itemID, $_SESSION['Email']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id = $row['itemID'];

    if (!$id) {
        $sql = $conn->prepare("INSERT INTO cart (name,price,qty,total_price,itemID,consignmentStoreID,rQ,customerEmail) VALUE (?,?,?,?,?,?,?,?)");
        $sql->bind_param("ssisiiis", $itemName, $itemPrice, $qty, $itemPrice, $itemID,$consignmentStoreID,$rQ,$_SESSION['Email']);
        $sql->execute();

        header("Location: ../makeOrder.php?msg=addsuccess");
        exit();
    } else {

        header("Location: ../makeOrder.php?msg=addfail");
        exit();
    }
} else {

    header("Location: ../makeOrder.php?msg=notset");
    exit();
}

