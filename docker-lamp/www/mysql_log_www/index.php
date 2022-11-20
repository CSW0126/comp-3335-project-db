<?php
require "header.php";
?>

<link rel="stylesheet" href="../css/index.css">
<!-- body section -->
<div id="particles-js" class="pimg1">
    <div class="header1">
        <div class="ptext">
            <span class="border trans">
                Welcome TO MySQL Logging Monitoring System
            </span>

            <?php
            //if logged in 
            if (isset($_SESSION['role'])) {

                if ($_SESSION['role'] == "admin") {
                    echo '
                    <h6>View MySQL General Log</h6>
                    <a href="mysql_general.php" class="bodybtn">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        View
                        </a>
                        ';
                } 
            } else {
                echo '
                <br />
                        <a href="login.php" class="bodybtn">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            Login
                        </a>
                        ';
            }
            ?>



        </div>
    </div>
</div>

<?php
require "footer.php";
?>