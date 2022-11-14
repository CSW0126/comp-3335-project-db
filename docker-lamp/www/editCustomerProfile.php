<?php
require "header.php";
?>
<link rel="stylesheet" href="css/signup.css">
<!-- main -->
<div class="body-container">
    <div class="container demo">
        <div class="content">
            <div id="large-header" class="large-header">
                <canvas id="demo-canvas"></canvas>
                <div class="form main-title center" data-aos="zoom-in">
                    <div class="title">
                        <h2>Update Your Profile</h2>
                    </div>
                    <?php
                    if (isset($_GET['error'])) {
                        //empty field
                        if ($_GET['error'] == "emptyfields") {
                            echo '<div id="error">Fill in all the message</div>';
                            //invalidNameMailPhone
                        } else if ($_GET['error'] == "invalidNamePhone") {
                            echo '<div id="error">Invalid Name and Phone</div>';
                            //invalidname
                        } else if ($_GET['error'] == "invalidname") {
                            echo '<div id="error">Invalid First name or last name</div>';
                            //check phone   
                        } else if ($_GET['error'] == "invalidname") {
                            echo '<div id="error">Invalid Phone : [2/5/6/8/9], 8 digits</div>';
                            //password
                        } else if ($_GET['error'] == "invalidpassword") {
                            echo '<div id="error">Not the same password</div>';
                            //phone duplicate
                        } else if ($_GET['error'] == "phoneTaken") {
                            echo '<div id="error">Phone already exists!</div>';
                        }
                    } else if (isset($_GET['update'])) {
                        if ($_GET['update'] == "success") {
                            echo '<div id="error"  style="color:green;">Profile updated!</div>';
                        }
                    }
                    ?>

                    <form method="post" enctype="multipart/form-data" id='form' name='form1' action="includes/editCustomer.inc.php">

                        <?php
                        if (isset($_SESSION['Email'])) {
                            //email print only
                            echo '
                                <div class="input_field textd">
                                    <label>Email</label>
                                    <label>' . $_SESSION['Email'] . '</label>
                                </div>
                                ';
                                
                            //first name
                            echo '
                                <div class="input_field textd">
                                    <label for="fname">First Name</label>
                                    <input id="fname" name="fname" type="text" class="input" value="' . $_SESSION['firstname'] . '">
                                </div>
                                ';

                            //last name
                            echo '
                                <div class="input_field textd">
                                    <label for="lname">Last Name</label>
                                    <input id="lname" name="lname" type="text" class="input" value="' . $_SESSION['lastname'] . '">
                                </div>
                                ';

                            echo '
                                <div class="input_field textd">
                                    <label for="phone">phone</label>
                                    <input id="phone" name="phone" type="text" class="input" value="'.$_SESSION['phoneNumber'].'">
                                </div>
                                ';
                        } else {
                            header('Location: index.php');
                            exit();
                        }
                        ?>

                        <div class="input_field textd">
                            <label for="pw">Password</label>
                            <input id="pw" name="pw" type="password" class="input">
                        </div>

                        <div class="input_field textd">
                            <label for="cpw">Confirm Password</label>
                            <input id="cpw" name="cpw" type="password" class="input">
                        </div>

                        <div class="input_field">
                            <input type="reset" class="btn clear" value="Clear">
                            <input id="submit" name="submit" type="submit" class="btn submit" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/499416/TweenLite.min.js"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/499416/EasePack.min.js"></script>
<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/499416/demo.js"></script>






<?php
require "footer.php";
?>