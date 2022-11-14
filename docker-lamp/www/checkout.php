<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<?php
require "header.php";
?>
<!-- check role -->
<?php
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != "customer") {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: login.php?role=customer");
    exit();
}
?>
<!-- css -->
<link rel="stylesheet" href="css/cart.css">
<!-- body -->

<div class="container cart-body">
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
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <?php
            require_once "connection/mysqli_conn.php";
            $sql = "SELECT COUNT(*),consignmentStoreID FROM cart GROUP BY consignmentStoreID";
            $rs = mysqli_query($conn, $sql);
            $selectedshopIDArr = array();

            $count = 0;
            while ($rowArray = mysqli_fetch_assoc($rs)) {

                echo "consignmentStoreID : {$rowArray['consignmentStoreID']}";

            ?>
                <div class="table-responsive mt-2">
                    <table class="table table-bordered table-striped text-centre mt-2">
                        <thead>
                            <tr>
                                <td colspan="7">
                                    <h2 class="text-center text-dark m-0">

                                        Order
                                        <?php
                                        $count++;
                                        echo $count;
                                        ?>
                                    </h2>
                                </td>
                            </tr>
                            <tr class="text-dark text-center justify-center">
                                <th>ItemID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>ConsignmentStoreName</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM cart where consignmentStoreID = ?");
                            $stmt->bind_param("i", $rowArray['consignmentStoreID']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $grand_total = 0;
                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr class="text-center">
                                    <th> <?= $row['itemID'] ?> </th>
                                    <input type="hidden" class="itemID" value="<?= $row['itemID'] ?>">

                                    <th><?= $row['name'] ?></th>

                                    <th><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($row['price'], 2) ?></th>
                                    <input type="hidden" class="itemPrice" value="<?= $row['price'] ?>">

                                    <th class="align-items-center">
                                        <?= $row['qty'] ?>
                                    </th>
                                    <input type="hidden" class="rQ" value="<?= $row['rQ'] ?>">
                                    <th><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp; <?= $row['total_price'] ?></th>

                                    <th><?php
                                        //get shop name
                                        $stmtGetShopName = $conn->prepare("SELECT * FROM consignmentstore where consignmentStoreID =?");
                                        $stmtGetShopName->bind_param("i",$row['consignmentStoreID']);
                                        $stmtGetShopName->execute();
                                        $shopResult = $stmtGetShopName->get_result();
                                        $shopArr=mysqli_fetch_assoc($shopResult);
                                        echo $shopArr['ConsignmentStoreName'];            
                                    ?></th>
                                </tr>
                            <?php $grand_total += $row['total_price'];
                            }
                            ?>
                            <tr class="text-center">
                                <td>
                                    <b>Pick Up Shop</b>
                                </td>
                                <td colspan="2">
                                    <?php
                                    $stmt2 = $conn->prepare("SELECT * FROM consignmentstore_shop WHERE consignmentStoreID = ?");
                                    $stmt2->bind_param("i", $rowArray['consignmentStoreID']);
                                    $stmt2->execute();
                                    $shopChoice = $stmt2->get_result(); ?>

                                    <select name="Shop" class="form-control shopID" id="<?= $count - 1 ?>">
                                        <?php while ($shopArr = mysqli_fetch_assoc($shopChoice)) { ?>
                                            <option value="<?= $shopArr['shopID'] ?>">
                                                <?php echo $shopArr['shopID'] ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>

                                </td>
                                <td>
                                    <b>Grand Total</b>
                                </td>
                                <td>
                                    <b><?php echo '<i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;' . number_format($grand_total, 2) ?></b>
                                </td>
                                <td colspan="2">
                                    <a href="makeOrder.php" class="btn btn-success"> Back to Shop </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            ?>

            <div class="mt-3 float-right">
                <?php


                ?>
                <button class="btn btn-danger confirm-order" id="confirm">Confirm</button>
                <input type="hidden" name="count" id="count" value="<?= $count ?>">
            </div>
        </div>
    </div>
</div>






<?php
require "footer.php";
?>

<script>
    $(document).ready(function() {

        //onclick
        $('.confirm-order').on('click', function() {
            if (confirm('Are you sure you want to confirm the orders?')) {
                var selectedValues = $('.shopID').map(function() {
                    return $(this).val();
                }).get();

                $.ajax({
                    url: 'includes/checkout.inc.php?count=<?= $count ?>',
                    method: 'post',
                    cache: false,
                    data: {
                        shop: selectedValues
                    },
                    success: function(response) {
                        console.log(response);
                        console.log('success');
                        console.log(selectedValues);
                        window.location.href = "viewOrder.php";

                    },
                    error: function() {
                        console.log('error');
                    }
                });

            }
        });
    });
</script>