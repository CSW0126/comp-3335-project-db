<?php
session_start();
if ($_SESSION['role'] != "customer") {
    header("Location: ../index.php");
    exit();
}
require '../connection/mysqli_conn_customer_user.php';
if(isset($_POST['completed'])){
    if(isset($_GET['orderID'])){
        $orderID = openssl_decrypt(base64_decode($_GET['orderID']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
        //awa value
        $status = 3;
        //change status
        $stmtUpdateOrder = $conn->prepare("UPDATE orders SET status=?  WHERE  orderID=? ");
        $stmtUpdateOrder->bind_param("ii",$status,$orderID);
        $stmtUpdateOrder->execute();
        header("Location: ../viewOrderDetails.php?orderID=".$_GET['orderID']."&status=success");
        exit();
    }

}else{
    header("Location: ../report.php");
    exit();
}

?>