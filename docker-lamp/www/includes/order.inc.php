<?php


require '../connection/mysqli_conn.php';

if (isset($_POST['itemID'])) {
    $itemID = $_POST['itemID'];
    $itemName = $_POST['itemName'];
    $itemPrice = $_POST['itemPrice'];
    $consignmentStoreID = $_POST['consignmentStoreID'];
    $rQ = $_POST['rQ'];
    $qty = 1;

    $stmt = $conn->prepare("SELECT itemID FROM cart WHERE itemID = ?");

    $stmt->bind_param("s", $itemID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id = $row['itemID'];




    if (!$id) {
        $sql = $conn->prepare("INSERT INTO cart (name,price,qty,total_price,itemID,consignmentStoreID,rQ) VALUE (?,?,?,?,?,?,?)");
        $sql->bind_param("ssisiii", $itemName, $itemPrice, $qty, $itemPrice, $itemID,$consignmentStoreID,$rQ);
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

