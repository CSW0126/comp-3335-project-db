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
<!-- css -->
<link rel="stylesheet" href="css/item.css">
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
    <div class="container">
        <?php
        if (isset($_GET['add'])) {
            if (isset($_GET['id'])) {
        ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Item #<?= $_GET['id'] ?> Added Successfully!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
            } else {
            ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Something wrong! Please try-again!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
            }
        } else if (isset($_GET['edit'])) {
            if (isset($_GET['id'])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Item #<?= $_GET['id'] ?> Edit Successfully!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
            } else {
            ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Something wrong! Please try-again!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
        <?php
            }
        }

        ?>
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2><b>Item List</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <a href="#addItem" class="btn btn-success" data-toggle="modal"><i class="far fa-plus-square"></i> <span>&nbsp;Add New Item</span></a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Store Name</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $num = 0;
                    //get item
                    if (isset($_SESSION['storeID'])) {
                        $tenantID = $_SESSION['tid'];
                        $storeID = $_SESSION['storeID'];
                        for ($i = 0; $i < count($storeID); $i++) {
                            $stmtGetItem = $conn->prepare("SELECT * FROM goods where consignmentStoreID = ? ORDER BY case when goodsName = 'fff' then 1 else 2 end, goodsName ASC");
                            $stmtGetItem->bind_param("i", $storeID[$i]);
                            $stmtGetItem->execute();
                            $itemResult = $stmtGetItem->get_result();
                            while ($itemRow = mysqli_fetch_assoc($itemResult)) {
                                $num++;

                    ?>
                                <tr>
                                    <td><?= $itemRow['goodsNumber'] ?></td>
                                    <td><?php
                                        //get shop name
                                        $stmtGetShopName = $conn->prepare("SELECT * FROM consignmentstore where consignmentStoreID =?");
                                        $stmtGetShopName->bind_param("i", $storeID[$i]);
                                        $stmtGetShopName->execute();
                                        $shopNameResult = $stmtGetShopName->get_result();
                                        $shopName = mysqli_fetch_assoc($shopNameResult);
                                        echo $shopName['ConsignmentStoreName'];
                                        ?>
                                    </td>
                                    <td><?= $itemRow['goodsName'] ?></td>
                                    <td>$ <?= $itemRow['stockPrice'] ?></td>
                                    <td><?= $itemRow['remainingStock'] ?></td>

                                    <?php
                                    if ($itemRow['status'] == 1) {
                                        echo '<td class="text-success">Available';
                                    } else if ($itemRow['status'] == 2) {
                                        echo '<td class="text-danger">Unavailable';
                                    } else {
                                        echo '<td class="text-danger">Unavailable';
                                    }
                                    ?>


                                    </td>
                                    <td>
                                        <a href="#Edit<?= $itemRow['goodsNumber'] ?>" class="edit" data-toggle="modal"><i class="fas fa-pencil-alt" data-toggle="tooltip" title="Edit"></i></a>
                                    </td>
                                </tr>
                                <!-- Edit Modal HTML -->
                                <div id="Edit<?= $itemRow['goodsNumber'] ?>" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="post" action="includes/itemControl.inc.php">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit Item #<?= $itemRow['goodsNumber'] ?></h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" name="name" value="<?= $itemRow['goodsName'] ?>" required>
                                                        <input type="hidden" name="goodsNumber" value="<?= base64_encode(openssl_encrypt($itemRow['goodsNumber'], $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd'])) ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Price</label>
                                                        <input type="number" class="form-control" name="price" value="<?= $itemRow['stockPrice'] ?>" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Stock</label>
                                                        <input type="number" class="form-control" name="stock" value="<?= $itemRow['remainingStock'] ?>" required></input>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Consign Store</label>
                                                        <select name="editConShop" id="editConShop" class="form-control" required>
                                                            <?php
                                                            for ($j = 0; $j < count($storeID); $j++) {
                                                            ?>
                                                                <option value="<?= base64_encode(openssl_encrypt($storeID[$j], $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd'])) ?>" <?php
                                                                                                    //each selected for the original store ID
                                                                                                    if ($itemRow['consignmentStoreID'] == $storeID[$j]) {
                                                                                                        echo 'selected';
                                                                                                    }

                                                                                                    ?>>
                                                                    <?php
                                                                    //get shop name
                                                                    $stmtGetStoreName = $conn->prepare("SELECT * FROM consignmentstore where consignmentStoreID=?");
                                                                    $stmtGetStoreName->bind_param("i", $storeID[$j]);
                                                                    $stmtGetStoreName->execute();
                                                                    $storeNameResult = $stmtGetStoreName->get_result();
                                                                    $storeName = mysqli_fetch_assoc($storeNameResult);
                                                                    echo $storeName['ConsignmentStoreName'];
                                                                    ?>
                                                                </option>

                                                            <?php
                                                            }
                                                            ?>


                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select name="status" id="status" class="form-control" required>
                                                            <option value="<?= base64_encode(openssl_encrypt(1, $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd'])) ?>" <?php
                                                                                if ($itemRow['status'] == 1) {
                                                                                    echo 'selected';
                                                                                }
                                                                                ?>>Available</option>
                                                            <option value="<?= base64_encode(openssl_encrypt(2, $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd'])) ?>" <?php
                                                                                if ($itemRow['status'] == 2) {
                                                                                    echo 'selected';
                                                                                }
                                                                                ?>>
                                                                Unavailable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                                                    <input type="submit" class="btn btn-info" name="save" value="Save">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                </tbody>
            </table>
            <div class="clearfix">
                <div class="hint-text">Showing <b><?= $num ?></b> Items</div>
            </div>
        </div>
    </div>
    <!-- Add Modal HTML -->
    <div id="addItem" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="includes/itemControl.inc.php">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Item</h4>
                        <input type="hidden" name="storeID" value="<?= $storeID ?>">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control" required></input>
                        </div>
                        <div class="form-group">
                            <label>Consign Store</label>
                            <select name="addConShop" id="addConShop" class="form-control" required>
                                <?php
                                for ($j = 0; $j < count($storeID); $j++) {
                                ?>
                                    <option value="<?= base64_encode(openssl_encrypt($storeID[$j], $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd'])) ?>">
                                        <?php
                                        //get shop name
                                        $stmtGetStoreName = $conn->prepare("SELECT * FROM consignmentstore where consignmentStoreID=?");
                                        $stmtGetStoreName->bind_param("i", $storeID[$j]);
                                        $stmtGetStoreName->execute();
                                        $storeNameResult = $stmtGetStoreName->get_result();
                                        $storeName = mysqli_fetch_assoc($storeNameResult);
                                        echo $storeName['ConsignmentStoreName'];
                                        ?>
                                    </option>

                                <?php
                                }
                                ?>


                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="<?= base64_encode(openssl_encrypt(1, $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd'])) ?>">Available</option>
                                <option value="<?= base64_encode(openssl_encrypt(2, $_SESSION['encrypt_method'], $_SESSION['encrypt_passwd'])) ?>">Unavailable</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-success" name="add" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
                    } else {
                        //if no store ID , logout go to index
                        session_destroy();
                        header("Location: index.php");
                        exit();
                    }
?>
<?php
require "footer.php";
?>