<?php
ob_start();
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
ob_end_flush();
?>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="css/hover.css">
<link rel="stylesheet" href="css/order.css">
<!-- body -->
<div class="container order-body">
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
    if (isset($_GET['msg'])) {
        if ($_GET['msg'] == "addsuccess") {
            echo '
            <div class="alert alert-success mt-2" role="alert">
                Item Added to the cart!
            </div>';
        } else {
            echo '
            <div class="alert alert-danger mt-2" role="alert">
                Item already in the cart!
            </div>';
        }
    }

    ?>
    <div class="row mt-2 pb-3">
        <?php
        $goodStatusAva = 1;
        $remainQuan = 0;
        $stmt = $conn->prepare("SELECT * From goods where status =? && remainingStock > ? ORDER BY case when goodsName = 'fff' then 1 else 2 end, goodsName ASC");
        $stmt->bind_param("ii", $goodStatusAva,$remainQuan);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-sm-6 col-md-4 col-lg-3 mb-2 ">
                <div class="card-deck" data-aos="zoom-in">
                    <div class="card p-2 border-secondary mb-2 hvr-grow">
                        <div class="card-body p-1">
                            <h4 class="card-title text-center text-info">
                                <?php echo "{$row['goodsName']}" ?>
                            </h4>
                            <!-- status -->
                            <?php
                            if ($row['status'] == 1) {
                                //available
                                echo '<h5 class="card-text text-center text-success">';
                                echo "Available";
                            } else {
                                //not available
                                echo '<h5 class="card-text text-center text-danger">';
                                echo "Not Available";
                            }
                            ?>
                            </h5>
                            <!-- price -->
                            <h5 class="card-text text-center goods-price">
                                <i class="fas fa-dollar-sign"></i>
                                <?php echo "{$row['stockPrice']}"; ?>
                            </h5>
                        </div>
                        <div class="card-footer p-1">
                            <form action="includes/order.inc.php" class="form-submit" method="post">
                                <input type="hidden" name="itemID" value="<?= $row['goodsNumber'] ?>">
                                <input type="hidden" name="itemName" value="<?= $row['goodsName'] ?>">
                                <input type="hidden" name="itemPrice" value="<?= $row['stockPrice'] ?>">
                                <input type="hidden" name="consignmentStoreID" value="<?= $row['consignmentStoreID'] ?>">
                                <input type="hidden" name="rQ" value="<?= $row['remainingStock'] ?>">

                                <?php
                                if ($row['status'] == 1) {
                                    echo '
                                        <button class="btn btn-info btn-block add-item-btn" type="submit">
                                            <i class="fas fa-cart-plus"></i>&nbsp;&nbsp;Add To Cart
                                        </button>';
                                } else {
                                    echo '
                                        <button class="btn btn-info btn-block add-item-btn" disabled>
                                            Not Available
                                        </button>';
                                }

                                ?>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        <?php } ?>
    </div>
</div>



<?php
require "footer.php";
?>