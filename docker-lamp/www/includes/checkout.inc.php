<?php
require_once "../connection/mysqli_conn.php";
session_start();

if (isset($_GET['count'])) {
    $count = $_GET['count'];
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
            //add total
            $grand_total += $row['total_price'];
        }

        //make order
        //var
        $email = $_SESSION['Email'];
        $storeID = $rowArray['consignmentStoreID'];
        $shopID = $selectedShopArr[$i];
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
