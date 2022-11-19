<?php
session_start();
if ($_SESSION['message'] == 'Remaining Stock Not Enough.') {
    header('location: ../cart.php');
    exit();
} else {
    header('location: ../viewOrder.php');
    exit();
}
?>