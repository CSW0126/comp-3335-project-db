<?php
session_start();
if ($_SESSION['role'] != "tenant") {
    header("Location: ../index.php");
    exit();
}
require '../connection/mysqli_conn_tenant_user.php';
if(isset($_POST['awaiting'])){
    if(isset($_GET['orderID'])){
        $orderID = openssl_decrypt(base64_decode($_GET['orderID']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
        //awa value
        $status = 2;
        //change status
        $stmtUpdateOrder = $conn->prepare("UPDATE orders SET status=?  WHERE  orderID=? ");
        $stmtUpdateOrder->bind_param("ii",$status,$orderID);
        $stmtUpdateOrder->execute();
        header("Location: ../viewOrderDetails.php?orderID=".$_GET['orderID']."&status=success");
        exit();
    }

}else if(isset($_POST['delete'])){
    if(isset($_GET['orderID'])){
        $orderID = openssl_decrypt(base64_decode($_GET['orderID']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
        //delete order ITEM
        $stmtDeleteOrderItem =$conn->prepare("DELETE FROM orderitem where orderID = ?");
        $stmtDeleteOrderItem->bind_param("i",$orderID);
        $stmtDeleteOrderItem->execute();

        //delete order
        $stmtDeleteOrder = $conn->prepare("DELETE FROM orders where orderID = ?");
        $stmtDeleteOrder->bind_param("i",$orderID);
        $stmtDeleteOrder->execute();
        header("Location: ../report.php?orderID=".$_GET['orderID']."&status=deleted");
        exit();
    }

}else{
    header("Location: ../report.php");
    exit();
}

?>