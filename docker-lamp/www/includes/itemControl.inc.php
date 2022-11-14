<?php
//add
if (isset($_POST['add'])) {
    require_once "../connection/mysqli_conn.php";
    $consignmentStoreID = $_POST['addConShop'];
    $goodsName = $_POST['name'];
    $stockPrice = $_POST['price'];
    $stock = $_POST['stock'];
    $status = $_POST['status'];
    $stmtAddItem = $conn->prepare("INSERT INTO goods (consignmentStoreID,goodsName,stockPrice,remainingStock,status) VALUE (?,?,?,?,?)");
    $stmtAddItem->bind_param("issii", $consignmentStoreID, $goodsName, $stockPrice, $stock, $status);
    $stmtAddItem->execute();
    $last_id = $conn->insert_id;

    header("Location: ../item.php?add=success&id=" . $last_id);
    exit();
    //edit    
} else if (isset($_POST['save'])) {
    require_once "../connection/mysqli_conn.php";
    $consignmentStoreID = $_POST['editConShop'];
    $goodsNumber = $_POST['goodsNumber'];
    $goodsName = $_POST['name'];
    $stockPrice = $_POST['price'];
    $stock = $_POST['stock'];
    $status = $_POST['status'];
    $stmtEditItem = $conn->prepare("UPDATE goods SET consignmentStoreID=?,goodsName=?,stockPrice=?,remainingStock=?,status=? where goodsNumber=?");
    $stmtEditItem->bind_param("issiii", $consignmentStoreID, $goodsName, $stockPrice, $stock, $status, $goodsNumber);
    $stmtEditItem->execute();

    header("Location: ../item.php?edit=success&id=" . $goodsNumber);
    exit();
} else {
    header("Location: index.php");
    exit();
}
