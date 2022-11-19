<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- font awesome -->
    <script type="text/javascript">
        (function() {
            var css = document.createElement('link');
            css.href = 'https://use.fontawesome.com/releases/v5.1.0/css/all.css';
            css.rel = 'stylesheet';
            css.type = 'text/css';
            document.getElementsByTagName('head')[0].appendChild(css);
        })();
    </script>
    <!-- CSS -->
    <link rel="stylesheet" href="css/global.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- title -->
    <title>Hong Kong Cube Shop</title>
</head>

<body>
    <?php
    //check cartQ
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] == "customer") {
            require 'connection/mysqli_conn_customer_user.php';
            //get num of cart
            $stmt = $conn->prepare("SELECT * from cart WHERE customerEmail = ?");
            $stmt->bind_param("s", $_SESSION['Email']);
            $stmt->execute();
            $stmt->store_result();
            $rows = $stmt->num_rows();
        } else if ($_SESSION['role'] == "tenant") {
            require 'connection/mysqli_conn_tenant_user.php';
        }
    }
    ?>


    <!-- header + navbar -->
    <header class="main-header">
        <div class="container">
            <nav class="navbar">
                <h1 class="home icon-link"><a href="index.php"><i class="fas fa-cubes"></i></a></h1>
                <input type="checkbox" class="menu-btn" id="menu-btn">
                <label for="menu-btn" class="menu-icon">
                    <span class="menu-icon__line"></span>
                </label>
                <ul class="nav-links">
                    <?php
                    //if logged in 
                    if (isset($_SESSION['role'])) {
                        echo '
                        <li class="nav-link"><a href="index.php">Home</a></li>';

                        //tenant , show "Rent"
                        if ($_SESSION['role'] == "tenant") {
                            echo '
                            <li class="nav-link"><a href="item.php">Item</a></li>
                            <li class="nav-link"><a href="report.php">Report</a></li>';
                            //customer, show buy
                        } else if ($_SESSION['role'] == "customer") {
                            echo '<li class="nav-link"><a href="makeOrder.php">Buy</a></li>
                            <li class="nav-link"><a href="viewcustomerprofile.php">Account</a></li>
                            <li class="nav-link"><a href="viewOrder.php">Record</a></li>
                            <li class="nav-link"><a href="cart.php"><i id="cart-item" class="fas fa-shopping-cart"></i> <span class="badge badge-danger">';


                            //cart number
                            echo "$rows";

                            echo '</span> </a></li>';
                        }
                        echo '
                        <li class="nav-link"><a href="includes/logout.inc.php">Logout</a></li>';
                        //if not logged in
                    } else {
                        echo '                    
                        <li class="nav-link"><a href="index.php">Home</a></li>
                        <li class="nav-link"><a href="login.php?role=' . 'customer' . '">Buy</a></li>
                        <li class="nav-link"><a href="login.php?role=' . 'tenant' . '">Rent</a></li>
                        <li class="nav-link"><a href="signup.php">Register</a></li>
                        ';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>
</body>

</html>