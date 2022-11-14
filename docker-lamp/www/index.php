<?php
require "header.php";
?>

<link rel="stylesheet" href="css/index.css">
<!-- body section -->
<div id="particles-js" class="pimg1">
    <div class="header1">
        <div class="ptext">
            <span class="border trans">
                Welcome
            </span>

            <?php
            //if logged in 
            if (isset($_SESSION['role'])) {

                if ($_SESSION['role'] == "tenant") {
                    echo '
                    <h6>Management Your Business</h6>
                    <a href="item.php" class="bodybtn">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        Item
                        </a>
                        ';
                } else if ($_SESSION['role'] == "customer") {
                    echo '
                    <h6>View items</h6>
                    <a href="makeOrder.php" class="bodybtn">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        Go
                        </a> 
                        ';

                    //if not logged in
                }
            } else {
                echo '
                        <h6>What is your role?</h6>
                        <a href="login.php?role=' . 'customer' . '" class="bodybtn">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            Customer
                        </a>
                        <a href="login.php?role=' . 'tenant' . '" class="bodybtn">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            Tenant
                        </a>';
            }
            ?>



        </div>
    </div>
</div>

<?php
require "footer.php";
?>