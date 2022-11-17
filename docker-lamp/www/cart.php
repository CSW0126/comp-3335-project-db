<?php
require "header.php";
?>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
            <div style="display: <?php
                                    if (isset($_SESSION['showAlert'])) {
                                        echo $_SESSION['showAlert'];
                                    } else {
                                        echo 'none';
                                    }
                                    unset($_SESSION['showAlert']);
                                    ?>;" class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                <strong>
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                    }
                    unset($_SESSION['message']);
                    ?>
                </strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="table-responsive mt-2">
                <table class="table table-bordered table-striped text-centre">
                    <thead>
                        <tr>
                            <td colspan="7">
                                <h2 class="text-center text-dark m-0">
                                    Products in your cart
                                </h2>
                            </td>
                        </tr>
                        <tr class="text-dark text-center justify-center">
                            <th>ItemID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>ConsignmentStoreID</th>
                            <th class="text-center">
                                <a href="includes/removeFromCart.inc.php?clear=all" class="badge-danger badge p-1 " onclick="return confirm('Are You Sure?')"><i class="fas fa-trash"></i>&nbsp;&nbsp;Delete All</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require "connection/mysqli_conn.php";
                        $stmt = $conn->prepare("SELECT * FROM cart WHERE customerEmail = ?");
                        $stmt->bind_param("s", $_SESSION['Email']);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $grand_total = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr class="text-center">
                                <th> <?= $row['itemID'] ?> </th>
                                <input type="hidden" class="itemID" value="<?= $row['itemID'] ?>">

                                <th><?= $row['name'] ?></th>

                                <th><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp;<?= number_format($row['price'], 2) ?></th>
                                <input type="hidden" class="itemPrice" value="<?= $row['price'] ?>">

                                <th class="align-items-center">
                                    <select name="QTY" class="form-control itemQty">
                                        <?php for ($i = 1; $i <= $row['rQ']; $i++) : ?>
                                            <option value="<?php echo $i; ?>" <?php
                                                                                if ($row['qty'] == $i) {
                                                                                    echo 'selected="selected"';
                                                                                }
                                                                                ?>><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </th>
                                <input type="hidden" class="rQ" value="<?= $row['rQ'] ?>">
                                <th><i class="fas fa-dollar-sign"></i>&nbsp;&nbsp; <?= $row['total_price'] ?></th>

                                <th><?= $row['consignmentStoreID'] ?></th>

                                <th>
                                    <a href="includes/removeFromCart.inc.php?remove=<?= $row['itemID'] ?>" class="text-danger lead p-1" onclick="return confirm('Are You Sure?')"><i class="fas fa-trash-alt fa-2x"></i></a></th>
                            </tr>
                        <?php $grand_total += $row['total_price'];
                        }
                        ?>
                        <tr class="text-center">
                            <td colspan="3">
                                <a href="makeOrder.php" class="btn btn-success"> Back to Shop </a>
                            </td>
                            <td>
                                <b>Grand Total</b>
                            </td>
                            <td>
                                <b><?php echo '<i class="fas fa-dollar-sign"></i>&nbsp&nbsp' . number_format($grand_total, 2) ?></b>
                            </td>
                            <td colspan="2">
                                <a href="checkout.php" class="btn btn-info <?= ($grand_total > 1) ? "" : "disabled"; ?>"><i class="fas fa-credit-card"></i>&nbsp;&nbsp;Check Out</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
require "footer.php";
?>

<script>
    $(document).ready(function() {
        $(".itemQty").on('change', function() {
            var $el = $(this).closest('tr');
            var itemID = $el.find(".itemID").val();
            var price = $el.find(".itemPrice").val();
            var qty = $el.find(".itemQty").val();
            var rQ = $el.find(".rQ").val();
            $.ajax({
                url: "includes/cart.inc.php",
                method: 'post',
                cache: false,
                data: {
                    qty: qty,
                    itemID: itemID,
                    price: price,
                    rQ: rQ,
                },
                success: function(response) {
                    console.log(response);
                }
            });
            location.reload(true);
        });
    });
</script>
