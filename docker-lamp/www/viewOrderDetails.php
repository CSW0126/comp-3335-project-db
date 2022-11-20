<?php
session_start();
ob_start();
if (($_SESSION['role'] == "tenant") ) {
} else if (($_SESSION['role'] == "customer")) {}
else { 
    header("Location: index.php");
    exit();
}
require "header.php";
?>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!-- css -->
<link rel="stylesheet" href="css/viewOrderDetails.css">

<?php
if (isset($_GET['orderID'])) {
    $role = $_SESSION['role'];
    if ($role == 'customer') {
        //check valid orderID and login customerEmail
        $stmt = $conn->prepare("SELECT * FROM orders WHERE orderID = ? && customerEmail = ?");
        $email = $_SESSION['Email'];
        $orderID = $_GET['orderID'];
        $stmt->bind_param("is", $orderID, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRow = mysqli_num_rows($result);
    } else if ($role == 'tenant') {
        if (isset($_SESSION['tid'])) {
            $stmt = $conn->prepare("SELECT * FROM orders, consignmentstore, tenant WHERE consignmentstore.consignmentStoreID = orders.consignmentStoreID AND consignmentstore.tenantID = tenant.tenantID AND orderID = ? AND tenant.tenantID = ?");
            $orderID = $_GET['orderID'];
            $stmt->bind_param("is", $orderID, $_SESSION['tid']);
            $stmt->execute();
            $result = $stmt->get_result();
            $numRow = mysqli_num_rows($result);
        } else {
            header("Location: index.php");
            exit();
        }
    }
?>


    <!-- body -->
    <div class="body-container">
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

        <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 padding form-body">
            <?php
            if (isset($_GET['status'])) {
                if ($_GET['status'] == "success") {
                    echo '
                    <div class="alert alert-success mt-2" data-aos="zoom-in-up" role="alert">
                        Status change to Awaiting!
                    </div>';
                }
            }

            ?>
            <div class="card" data-aos="zoom-in-up">
                <div class="card-header p-4">
                    <div class="float-left">
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <h3 class="mb-0">Order No : #<?= $row['orderID'] ?></h3>
                            <?= $row['orderDateTime'] ?>
                    </div>
                    <div class="float-right">
                        <?php
                            if ($row['status'] == 1 && $role == 'tenant') { ?>
                            <div class="mb-2">
                                <a href="#Awaiting" class="btn btn-success" data-toggle="modal"><i class="far fa-plus-square"></i> <span>&nbsp;Ready for pick</span></a>
                            </div>
                            <div class="mt-2">
                                <a href="#DeleteOrderModal" class="btn btn-danger w-100" data-toggle="modal"><i class="far fa-trash-alt"></i> <span>&nbsp;Delete Order</span></a>
                            </div>
                        <?php
                            }
                        ?>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h5 class="mb-3">Customer Email:</h5>
                            <h4 class="text-dark mb-1"><?= $row['customerEmail'] ?></h4>
                            <br>
                            <h5 class="mb-3">Order Status : </h5>
                            <?php
                            //Delivery
                            if ($row['status'] == 1) {
                                echo '<h4 class=" mb-1 text-white border float-left bg-warning p-2">Delivery</h4>';
                                //Awaiting
                            } else if ($row['status'] == 2) {
                                echo '<h4 class=" mb-1 text-white border float-left bg-success p-2">Awaiting</h4>';
                                //Completed
                            } else if ($row['status'] == 3) {
                                echo '<h4 class="mb-1 text-white border float-left bg-info p-2">Completed</h4>';
                            }
                            ?>

                        </div>
                        <div class="col-sm-6 ">
                            <h5 class="mb-3">Pick up Shop No:</h5>
                            <h4 class="text-dark mb-1"># <?= $row['shopID'] ?></h4>
                            <br>
                            <div>Address: </div>
                            <?php
                            $stmtGetShopAdd = $conn->prepare("SELECT * FROM shop where shopID =? ");
                            $stmtGetShopAdd->bind_param("i", $row['shopID']);
                            $stmtGetShopAdd->execute();
                            $shopResult = $stmtGetShopAdd->get_result();
                            $shopAddress = mysqli_fetch_assoc($shopResult);

                            ?>
                            <div><?= $shopAddress['address'] ?></div>
                        </div>
                    </div>

                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                //get order Item
                                $stmtGetOrderItem = $conn->prepare("SELECT * FROM orderitem where orderID = ?");
                                $stmtGetOrderItem->bind_param("i", $row['orderID']);
                                $stmtGetOrderItem->execute();
                                $orderItemResult = $stmtGetOrderItem->get_result();
                                $printListCount = 1;

                                while ($orderItemRow = mysqli_fetch_assoc($orderItemResult)) { ?>
                                    <tr class="text-center">
                                        <td><?= $printListCount ?></td>
                                        <td>
                                            <?php
                                            //get item name
                                            $stmtGetItemName = $conn->prepare("SELECT * FROM goods where goodsNumber = ?");
                                            $stmtGetItemName->bind_param("i", $orderItemRow['goodsNumber']);
                                            $stmtGetItemName->execute();
                                            $itemResult = $stmtGetItemName->get_result();
                                            $itemAss = mysqli_fetch_assoc($itemResult);
                                            echo $itemAss['goodsName'];
                                            ?>
                                        </td>
                                        <td><?= $orderItemRow['quantity'] ?></td>
                                        <td><?= $orderItemRow['sellingPrice'] ?></td>
                                        <td>
                                            <?php
                                            $totalPerRow = $orderItemRow['quantity'] * $orderItemRow['sellingPrice'];
                                            echo '$&nbsp;&nbsp;' . number_format($totalPerRow, 2);
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                    $printListCount++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-sm-5">
                        </div>
                        <div class="col-lg-4 col-sm-5 ml-auto">
                            <table class="table table-clear">
                                <tbody>
                                    <tr>
                                        <td class="left">
                                            <strong class="text-dark">Total</strong> </td>
                                        <td class="right">
                                            <strong class="text-dark">$&nbsp;&nbsp;<?= $row['totalPrice'] ?></strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <p class="mb-0">* To return or exchange, please bring product with box and the receipt to the shop counter within 7 days of purchase.</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Modal HTML -->
    <div id="DeleteOrderModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="includes/orderControl.inc.php?orderID=<?= $_GET['orderID'] ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Orders</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete the Order?</p>
                        <p class="text-warning"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-danger" name="delete" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ready to pick Modal HTML -->
    <div id="Awaiting" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="includes/orderControl.inc.php?orderID=<?= $_GET['orderID'] ?>">
                    <div class="modal-header">
                        <h4 class="modal-title">Change Status</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Is the order ready to pick up?</p>
                        <p class="text-warning"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-success" name="awaiting" value="Ready">
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php

                        }
                        //if no result redirect
                        if ($numRow == 0) {
                            // header("Location: index.php");
                            // exit();
                        }
                    } else {
                        header("Location: index.php");
                        exit();
                    }

?>


<?php
require "footer.php";
ob_end_flush();
?>