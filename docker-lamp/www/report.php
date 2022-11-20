<?php
session_start();
if ($_SESSION['role'] != "tenant") {
    header("Location: index.php");
    exit();
}
require "header.php";
?>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="css/report.css">
<!-- body -->
<div class="container body-container">
    <div class="anim">
        <ui class="box-area">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ui>
    </div>
    <?php
    if (isset($_GET['status']) && isset($_GET['orderID'])) {
        if ($_GET['status'] == "deleted") {
            echo '
                    <div class="alert alert-success mt-2" data-aos="zoom-in-up" role="alert">
                        Order #' . openssl_decrypt(base64_decode($_GET['orderID']), $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd']) . ' Deleted!
                    </div>';
        }
    }

    ?>
    <div class="table-wrapper" data-aos="zoom-in">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-6">
                    <h2><b>Processing Orders</b></h2>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr class="text-center">
                    <th>OrderID</th>
                    <th>ConsignmentStoreID</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>PickUp Shop</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //get tenant's store ID
                if (isset($_SESSION['storeID'])) {
                    $tenantID = $_SESSION['tid'];
                    $store = $_SESSION['storeID'];
                } else {
                    header("Location: ../index.php");
                    exit();
                }


                //get tenant order

                $processNum = 0;
                for ($i = 0; $i < count($store); $i++) {
                    $stmtGetOrder = $conn->prepare("SELECT * FROM orders where status != ? && consignmentStoreID =? ORDER BY orderDateTime DESC");
                    $statusVal = 3;
                    $stmtGetOrder->bind_param("is", $statusVal,  $store[$i]);
                    $stmtGetOrder->execute();
                    $orderResult = $stmtGetOrder->get_result();

                    while ($orderRow = mysqli_fetch_assoc($orderResult)) {
                        $processNum++; ?>
                        <tr class="text-center">
                            <td><?= $orderRow['orderID'] ?></td>
                            <td><?= $orderRow['consignmentStoreID'] ?></td>
                            <td><?= $orderRow['orderDateTime'] ?></td>
                            <td>
                                <p class="card-text text-center 
                                
                                <?php
                                if ($orderRow['status'] == 1) {
                                    echo 'text-warning';
                                } else if ($orderRow['status'] == 2) {
                                    echo 'text-success';
                                }
                                ?>
                            "><?php
                                if ($orderRow['status'] == 1) {
                                    echo 'Delivery';
                                } else if ($orderRow['status'] == 2) {
                                    echo 'Awaiting';
                                }
                                ?></p>
                            </td>
                            <td>$&nbsp;&nbsp;<?= $orderRow['totalPrice'] ?></td>
                            <td><?=$orderRow['shopID']?></td>
                            <td><a href="viewOrderDetails.php?orderID=<?= base64_encode(openssl_encrypt($orderRow['orderID'], $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd'])) ?>"><i class="fas fa-search fa-2x"></i></a></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="clearfix">
            <div class="hint-text">Showing <b><?= $processNum ?></b> records</div>
        </div>
    </div>



    <div class="table-wrapper" data-aos="zoom-in">
        <div class="table-title2 table-title">
            <div class="row">
                <div class="col-sm-6">
                    <h2><b>Finished Orders</b></h2>
                </div>
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr class="text-center">
                    <th>OrderID</th>
                    <th>ShopID</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $numFinish = 0;
                for ($j = 0; $j < count($store); $j++) {
                    $stmtFinishOrder = $conn->prepare("SELECT * FROM orders WHERE status = ? && consignmentStoreID=? ORDER BY orderDateTime desc");
                    //select finished order
                    $finishOrderStatusVal = 3;
                    $stmtFinishOrder->bind_param('is', $finishOrderStatusVal,  $store[$j]);
                    $stmtFinishOrder->execute();
                    $finishOrderResult = $stmtFinishOrder->get_result();

                    while ($finRow = mysqli_fetch_assoc($finishOrderResult)) {
                        $numFinish++;

                ?>
                        <tr class="text-center">
                            <td><?= $finRow['orderID'] ?></td>
                            <td><?= $finRow['shopID'] ?></td>
                            <td><?= $finRow['orderDateTime'] ?></td>
                            <td>
                                <p class="card-text text-center text-primary">Completed</p>
                            </td>
                            <td>$&nbsp;&nbsp;<?= $finRow['totalPrice'] ?></td>
                            <td><a href="viewOrderDetails.php?orderID=<?= $finRow['orderID'] ?>"><i class="fas fa-search fa-2x"></i></a></td>
                        </tr>



                <?php
                    }
                }
                ?>

            </tbody>
        </table>
        <div class="clearfix">
            <div class="hint-text">Showing <b><?= $numFinish ?></b> records</div>
        </div>
    </div>
</div>


<?php
require "footer.php";
?>