<?php
require "header.php";
?>

<!-- css -->
<link rel="stylesheet" href="../css/login.css">
<!-- body section -->
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
    <div class="wrapper">
        <?php

        //if logged in 
        if (isset($_SESSION['adminID'])) {
            echo '
                    <div class="fcontainer" data-aos="zoom-in">
                        <form class="form" action="includes/logout.inc.php" method="POST">
                            <h1>Logout</h1>
                            <button type="submit" id="logout-button" name="logout">Logout</button>
                        </form>
                    </div>';
            //if not logged in
        } else {
                echo '
                        <div class="fcontainer" data-aos="zoom-in" >
                            <form class="form" action="includes/login.inc.php" method="POST">
                                <h1>Sign in</h1>';
                //if error
                if (isset($_GET['error'])) {
                    echo '<div id="error">Invalid password or admin ID</div>';
                }
                echo '       
                                <input type="hidden" name="role" value = "admin">
                                <input type="text" placeholder="Admin ID" name="adminID">
                                <input type="password" placeholder="Password" name="pwd">
                                <button type="submit" id="login-button" name="login">Login</button>
                            </form>
                        </div>';
        }

        ?>
    </div>
</div>


<?php
require "footer.php";
?>