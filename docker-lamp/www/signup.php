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
                        <h2>Registration Form</h2>
                    </div>
                    <?php
                    if (isset($_GET['error'])) {
                        //empty field
                        if ($_GET['error'] == "emptyfields") {
                            echo '<div id="error">Fill in all the message</div>';
                            //invalidNameMailPhone
                        } else if ($_GET['error'] == "invalidNameMailPhone") {
                            echo '<div id="error">Invalid Name Mail and Phone</div>';
                            //invalidmail
                        } else if ($_GET['error'] == "invalidmail") {
                            echo '<div id="error">Invalid Email</div>';
                            //invalidname
                        } else if ($_GET['error'] == "invalidname") {
                            echo '<div id="error">Invalid First name or last name</div>';
                            //check phone   
                        } else if ($_GET['error'] == "invalidphone") {
                            echo '<div id="error">Invalid Phone : [2/5/6/8/9], 8 digits</div>';
                            //password
                        } else if ($_GET['error'] == "invalidpassword") {
                            echo '<div id="error">Not the same password</div>';
                            //email duplicate
                        } else if ($_GET['error'] == "emailTaken") {
                            echo '<div id="error">Email already exists!</div>';
                            //phone duplicate
                        } else if ($_GET['error'] == "phoneTaken") {
                            echo '<div id="error">Phone already exists!</div>';
                        }
                    } else if (isset($_GET['signup'])) {
                        if ($_GET['signup'] == "success") {
                            echo '<div id="error"  style="color:green;">Account Created!</div>';
                        }
                    }
                    ?>

                    <form method="post" enctype="multipart/form-data" id='form' name='form1' action="includes/signup.inc.php">
                        <div class="input_field textd">
                            <label for="fname">First Name</label>
                            <input id="fname" name="fname" type="text" class="input">
                        </div>

                        <div class="input_field textd">
                            <label for="lname">Last Name</label>
                            <input id="lname" name="lname" type="text" class="input">
                        </div>

                        <div class="input_field textd">
                            <label for="pw">Password</label>
                            <input id="pw" name="pw" type="password" class="input">
                        </div>

                        <div class="input_field textd">
                            <label for="cpw">Confirm Password</label>
                            <input id="cpw" name="cpw" type="password" class="input">
                        </div>

                        <div class="input_field textd">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="text" class="input">
                        </div>

                        <div class="input_field textd">
                            <label for="phone">phone</label>
                            <input id="phone" name="phone" type="text" class="input">
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