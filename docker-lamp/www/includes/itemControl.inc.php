<?php
//add
session_start();
if ($_SESSION['role'] != "tenant") {
    header("Location: ../index.php");
    exit();
}
require_once '../connection/mysqli_conn_tenant_user.php';
if (isset($_POST['add'])) {
    $consignmentStoreID = openssl_decrypt(base64_decode($_POST['addConShop']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
    $goodsName = $_POST['name'];
    $stockPrice = $_POST['price'];
    $stock = $_POST['stock'];
    $status = openssl_decrypt(base64_decode($_POST['status']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
    $stmtAddItem = $conn->prepare("INSERT INTO goods (consignmentStoreID,goodsName,stockPrice,remainingStock,status) VALUE (?,?,?,?,?)");
    $stmtAddItem->bind_param("issii", $consignmentStoreID, $goodsName, $stockPrice, $stock, $status);
    $stmtAddItem->execute();
    $last_id = $conn->insert_id;

    header("Location: ../item.php?add=success&id=" . $last_id);
    exit();
    //edit    
} else if (isset($_POST['save'])) {
    $consignmentStoreID = openssl_decrypt(base64_decode($_POST['editConShop']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
    $goodsNumber = openssl_decrypt(base64_decode($_POST['goodsNumber']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
    $goodsName = $_POST['name'];
    $stockPrice = $_POST['price'];
    $stock = $_POST['stock'];
    $status = openssl_decrypt(base64_decode($_POST['status']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
    $stmtEditItem = $conn->prepare("UPDATE goods SET consignmentStoreID=?,goodsName=?,stockPrice=?,remainingStock=?,status=? where goodsNumber=?");
    $stmtEditItem->bind_param("issiii", $consignmentStoreID, $goodsName, $stockPrice, $stock, $status, $goodsNumber);
    $stmtEditItem->execute();

    header("Location: ../item.php?edit=success&id=" . $goodsNumber);
    exit();
} else {
    header("Location: ../index.php");
    exit();
}
