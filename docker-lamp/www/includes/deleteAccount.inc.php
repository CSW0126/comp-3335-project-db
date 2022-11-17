<?php

if (isset($_POST['confirm-btn'])) {
    require_once "../connection/mysqli_conn.php";
    session_start();
    $email = $_SESSION['Email'];
    //clean cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE customerEmail=?");
    $stmtFindOrder->bind_param("s",$email);
    $stmt->execute();
    //find orders
    $stmtFindOrder = $conn->prepare("SELECT * FROM orders where customerEmail = ?");
    $stmtFindOrder->bind_param("s",$email);
    $stmtFindOrder->execute();
    $orderResult = $stmtFindOrder->get_result();

    //for each orders
    while($orderRow = mysqli_fetch_assoc($orderResult)){
        //delete orders item
        $stmtDeleteOrderItem = $conn->prepare("DELETE FROM orderitem where orderID=?");
        $stmtDeleteOrderItem->bind_param("i",$orderRow['orderID']);
        $stmtDeleteOrderItem->execute();

        //delete order
        $stmtDeleteOrder = $conn->prepare("DELETE FROM orders where orderID = ?");
        $stmtDeleteOrder->bind_param("i",$orderRow['orderID']);
        $stmtDeleteOrder->execute();
    }

    //delete account
    $stmtDeleteAccount = $conn->prepare("DELETE FROM customer where customerEmail = ?");
    $stmtDeleteAccount->bind_param("s",$email);
    $stmtDeleteAccount->execute();

    session_destroy();
    header("Location: ../index.php");
    exit();

} else {
    header("Location: ../viewcustomerprofile.php");
    exit();
}
