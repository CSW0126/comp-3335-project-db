<?php

session_start();
session_unset();
session_destroy();
require_once "../connection/mysqli_conn.php";
$stmt = $conn->prepare("DELETE FROM cart");
$stmt->execute();
header("Location: ../index.php");