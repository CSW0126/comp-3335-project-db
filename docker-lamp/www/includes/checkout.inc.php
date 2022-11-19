<?php
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == "customer") {
        require_once '../connection/mysqli_conn_customer_user.php';
    }
}
if (isset($_POST['count'])) {
    $count = openssl_decrypt(base64_decode($_POST['count']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
    $selectedShopArr = $_POST['shop'];
    $i = 0;
    

    $stmtAddOrder = $conn->prepare("INSERT INTO orders (orderID,customerEmail,consignmentStoreID,shopID,orderDateTime,status,totalPrice)VALUE (DEFAULT,?,?,?,NOW(),?,?)");
    $stmtAddOrderItem = $conn->prepare("INSERT INTO orderitem (orderID,goodsNumber,quantity,sellingPrice) VALUE (?,?,?,?)");
    $stmtSelectGoods = $conn->prepare("SELECT * FROM goods where goodsNumber=?");
    $stmtReduceStock = $conn->prepare("UPDATE goods SET remainingStock=? WHERE goodsNumber=?");
    $stmt = $conn->prepare("SELECT * FROM cart where consignmentStoreID = ? AND customerEmail=?");
    //group with consignment store id
    $sql = $conn->prepare("SELECT COUNT(*),consignmentStoreID FROM cart WHERE customerEmail=? GROUP BY consignmentStoreID");
    $sql->bind_param("s", $_SESSION['Email']);
    $sql->execute();
    $rs = $sql->get_result();

    //for each order group
    while ($rowArray = mysqli_fetch_assoc($rs)) {
        //select all related goods in cart
        $stmt->bind_param("is", $rowArray['consignmentStoreID'], $_SESSION['Email']);
        $stmt->execute();
        $result = $stmt->get_result();
        $grand_total = 0;
        //for each item 
        while ($row = mysqli_fetch_assoc($result)) {
            $stmtCheckStock = $conn->prepare("SELECT * FROM goods WHERE goodsNumber=?");
            $stmtCheckStock->bind_param("s", $row['itemID']);
            $stmtCheckStock->execute();
            $stockResult = $stmtCheckStock->get_result();
            if ($row2 = mysqli_fetch_assoc($stockResult)){
                if ($row['rQ'] > $row2['remainingStock']) {
                    $total_price = $row2['remainingStock'] * $row2['stockPrice'];
                    $stmt1 = $conn->prepare("UPDATE cart SET name=?, qty=?, consignmentStoreID=?, total_price=?, price=?, rQ=? WHERE itemID=? AND customerEmail=?");
                    $stmt1->bind_param("siiiiiss",$row2['goodsName'], $row2['remainingStock'], $row2['consignmentStoreID'], $total_price, $row2['stockPrice'], $row2['remainingStock'], $row['itemID'], $_SESSION['Email']);
                    $stmt1->execute();
                    $_SESSION['showAlert'] = 'block';
                    $_SESSION['message'] = 'Remaining Stock Not Enough.';
                    exit();
                }
            }
            //add total
            $grand_total += $row['total_price'];
        }

        //make order
        //var
        $email = $_SESSION['Email'];
        $storeID = $rowArray['consignmentStoreID'];
        $shopID = openssl_decrypt(base64_decode($selectedShopArr[$i]), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']);
        $status = 1;
        //run sql
        $stmtAddOrder->bind_param("siiis", $email, $storeID, $shopID, $status, $grand_total);
        $stmtAddOrder->execute();
        $newOrderID = mysqli_insert_id($conn);

        //add order ITEM
        $stmt->bind_param("is", $rowArray['consignmentStoreID'], $_SESSION['Email']);
        $stmt->execute();
        $getCartResult = $stmt->get_result();
        while($itemRow = mysqli_fetch_assoc($getCartResult)){
            
            //add order ITEM
            $itemID = $itemRow['itemID'];
            $itemQTY = $itemRow['qty'];
            $itemPrice = $itemRow['price'];
            $stmtAddOrderItem->bind_param("iiis",$newOrderID,$itemID,$itemQTY,$itemPrice);
            $stmtAddOrderItem->execute();

            //get goods stock
            $stmtSelectGoods->bind_param("i",$itemID);
            $stmtSelectGoods->execute();
            $goodsResult = $stmtSelectGoods->get_result();
            $rowGoods= mysqli_fetch_assoc($goodsResult);

            setcookie($rowGoods['remainingStock'],$rowGoods['remainingStock'],time()+60);
            //reduce stock
            $newStockQTY = $rowGoods['remainingStock'] - $itemRow['qty'];
            $stmtReduceStock->bind_param("ii",$newStockQTY,$itemRow['itemID']);
            $stmtReduceStock->execute();
        }

        $i++;
    }
    echo 'no_error';
    //remove all item from cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE customerEmail=?");
    $stmt->bind_param("s", $_SESSION['Email']);
    $stmt->execute();
} else {
    header("location: ../index.php");
    exit();
}
