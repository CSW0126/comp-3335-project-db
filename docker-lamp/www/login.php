<?php
require "header.php";
?>

<!-- css -->
<link rel="stylesheet" href="css/login.css">
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
        if (isset($_SESSION['Email'])) {
            echo '
                    <div class="fcontainer" data-aos="zoom-in">
                        <form class="form" action="includes/logout.inc.php" method="POST">
                            <h1>Logout</h1>
                            <button type="submit" id="logout-button" name="logout">Logout</button>
                        </form>
                    </div>';
            //if not logged in
        } else {
            if (isset($_GET['role'])) {
                $roletype = $_GET['role'];
            } else {
                $roletype = 'notSelect';
            }

            //if is customer
            if ($roletype == 'customer') {
                echo '
                        <div class="fcontainer" data-aos="zoom-in" >
                            <form class="form" action="includes/login.inc.php" method="POST">
                                <h1>Sign in (Customer)</h1>';

                //if error
                if (isset($_GET['error'])) {
                    echo '<div id="error">Invalid password or Email</div>';
                }
                echo '
                        <input type="hidden" name="role" value = "customer">
                                <input type="text" placeholder="Email" name="email">
                                <input type="password" placeholder="Password" name="pwd">
                                <button type="submit" id="login-button" name="login">Login</button>
                            </form>
                        </div>';

                //if is tenant    
            } else if ($roletype == 'tenant') {
                echo '
                        <div class="fcontainer" data-aos="zoom-in" >
                            <form class="form" action="includes/login.inc.php" method="POST">
                                <h1>Sign in (Tenant)</h1>';
                //if error
                if (isset($_GET['error'])) {
                    echo '<div id="error">Invalid password or Tenant ID</div>';
                }
                echo '       
                                <input type="hidden" name="role" value = "tenant">
                                <input type="text" placeholder="TenantID" name="tid">
                                <input type="password" placeholder="Password" name="pwd">
                                <button type="submit" id="login-button" name="login">Login</button>
                            </form>
                        </div>';
            } else {
                header("Location: index.php");
                exit();
            }
        }

        ?>
    </div>
</div>


<?php
require "footer.php";
?>