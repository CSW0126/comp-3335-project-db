<?php
require "header.php";
?>
<link rel="stylesheet" href="css/profileCard.css">
<!-- flip-card-container -->

<div class=" body-container">
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
    <div class="flip-card-container body-container center" style="--hue: 220">
        <div class="flip-card">

            <div class="card-front">
                <figure>
                    <div class="img-bg"></div>
                    <img src="img/profileCard2.jpg" alt="Brohm Lake">
                    <figcaption>Customer Profile</figcaption>
                </figure>
                <ul class="ul-info">
                    <?php
                    $user = $_SESSION['Email'];
                    $sql = "SELECT customerEmail,firstName,lastName,phoneNumber FROM customer WHERE customerEmail = '$user' ";
                    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                    $resultCheck = mysqli_num_rows($result);

                    if ($resultCheck > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '
                            <li class="li-info"> </li>
                            <li class="li-info">' . $row['customerEmail'] . '</li>
                            <li class="li-info">' . $row['firstName'] . '</li>
                            <li class="li-info">' . $row['lastName'] . '</li>
                            <li class="li-info">' . $row['phoneNumber'] . '</li>
                            ';
                        }
                    }
                    ?>
                </ul>
            </div>

            <div class="card-back">
                <figure>
                    <div class="img-bg"></div>
                    <img src="img/profileCard1.jpg" alt="Brohm Lake">
                </figure>
                <form action="editcustomerprofile.php" method="POST" class="btn-edit">
                    <button class="btn btn-edit"> Edit</button>
                </form>

                <div class="design-container">
                    <span class="design design--1"></span>
                    <span class="design design--2"></span>
                    <span class="design design--3"></span>
                    <span class="design design--4"></span>
                    <span class="design design--5"></span>
                    <span class="design design--6"></span>
                    <span class="design design--7"></span>
                    <span class="design design--8"></span>
                </div>
                <button class="btn btn-delete" id="del-btn" onclick="confirmDia()">Delete</button>
            </div>

        </div>
    </div>
</div>
<!-- /flip-card-container -->

<div class="con-wrapper"  id="dia-confirm" role="dialog">
    <div class="overlay"></div>
    <div class="con-fcontainer" data-aos="zoom-in">
        <button type="cancel" id="cancel-btn" name="cancel-btn" onclick="confirmDia()">Cancel</button>
        <form class="form" action="includes/deleteAccount.inc.php" method="POST">
            <h1 class="dia-h1">Are you Sure?</h1>
            <button type="submit" id="confirm-btn" name="confirm-btn">Confirm</button>
        </form>
    </div>
</div>

<script src="js/confirm.js"></script>
<?php
require "footer.php";
?>