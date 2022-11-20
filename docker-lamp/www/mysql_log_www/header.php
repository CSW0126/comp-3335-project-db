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
    <link rel="stylesheet" href="../css/global.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- title -->
    <title>Hong Kong Cube Shop</title>
</head>

<body>
    


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
                        if ($_SESSION['role'] == "admin") {
                            echo '
                            <li class="nav-link"><a href="mysql_general.php">General Log</a></li>
                            <li class="nav-link"><a href="mysql_general_alert.php">General Log Alert</a></li>
                            <li class="nav-link"><a href="mysql_slow.php">Slow Log</a></li>
                            <li class="nav-link"><a href="mysql_error.php">Error Log</a></li>';
                            //customer, show buy
                        }
                        echo '
                        <li class="nav-link"><a href="includes/logout.inc.php">Logout</a></li>';
                        //if not logged in
                    } else {
                        echo '                    
                        <li class="nav-link"><a href="index.php">Home</a></li>
                        <li class="nav-link"><a href="login.php' . '">Login</a></li>
                        ';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>
</body>

</html>