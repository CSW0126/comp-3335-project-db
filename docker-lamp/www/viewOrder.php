<?php
session_start();
if ($_SESSION['role'] != "customer") {
    header("Location: index.php");
    exit();
}
require "header.php";
?>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!-- css -->
<link rel="stylesheet" href="css/viewOrder.css">

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
                    <th>ShopID</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM orders WHERE status != ? && customerEmail=? ORDER BY orderDateTime desc");
                //select not finish order
                $email = $_SESSION['Email'];
                $statusVal = 3;
                $stmt->bind_param("is", $statusVal, $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $num = mysqli_num_rows($result);

                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class="text-center">
                        <td><?= $row['orderID'] ?></td>
                        <td><?= $row['shopID'] ?></td>
                        <td><?= $row['orderDateTime'] ?></td>
                        <td>
                            <p class="card-text text-center 
                            
                            <?php
                            if ($row['status'] == 1) {
                                echo 'text-warning';
                            } else if ($row['status'] == 2) {
                                echo 'text-success';
                            }
                            ?>
                        "><?php
                            if ($row['status'] == 1) {
                                echo 'Delivery';
                            } else if ($row['status'] == 2) {
                                echo 'Awaiting';
                            }
                            ?></p>
                        </td>
                        <td>$&nbsp;&nbsp;<?= $row['totalPrice'] ?></td>
                        <td><a href="viewOrderDetails.php?orderID=<?= $row['orderID'] ?>"><i class="fas fa-search fa-2x"></i></a></td>
                    </tr>


                <?php
                }
                ?>


            </tbody>
        </table>
        <div class="clearfix">
            <div class="hint-text">Showing <b><?= $num ?></b> records</div>
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
                $stmtFinishOrder = $conn->prepare("SELECT * FROM orders WHERE status = ? && customerEmail=? ORDER BY orderDateTime desc");
                //select finished order
                $finishOrderStatusVal = 3;
                $stmtFinishOrder->bind_param('is', $finishOrderStatusVal, $email);
                $stmtFinishOrder->execute();
                $finishOrderResult = $stmtFinishOrder->get_result();
                $numOfFinishOrder = mysqli_num_rows($finishOrderResult);

                while ($finRow = mysqli_fetch_assoc($finishOrderResult)) { ?>
                    <tr class="text-center">
                        <td><?= $finRow['orderID'] ?></td>
                        <td><?= $finRow['shopID'] ?></td>
                        <td><?= $finRow['orderDateTime'] ?></td>
                        <td>
                            <p class="card-text text-center text-primary">Completed</p>
                        </td>
                        <td>$&nbsp;&nbsp;<?= $finRow['totalPrice'] ?></td>
                        <td><a href="viewOrderDetails.php?orderID=<?= base64_encode(openssl_encrypt($finRow['orderID'], $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd'])) ?>"><i class="fas fa-search fa-2x"></i></a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="clearfix">
            <div class="hint-text">Showing <b><?= $numOfFinishOrder ?></b> records</div>
        </div>
    </div>
</div>



<?php
require "footer.php";
?>