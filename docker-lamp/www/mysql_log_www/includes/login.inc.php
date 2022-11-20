<?php

ob_start();
if (isset($_POST['role'])) {
    if ($_POST['role'] == 'admin') {
        if (isset($_POST['login'])) {

            $hostname="db";
            $username = $_POST['adminID'];
            $pwd=$_POST['pwd'];
            $db = "mysql";
            $conn=mysqli_connect($hostname,$username,$pwd,$db) ;

            //check empty field
            if (empty($username) || empty($pwd)) {
                header("Location: ../login.php?error=emptyfields&role=tenant");
                exit();
            } else {
                if ($conn) { 
                    session_start();
                    $_SESSION['adminID'] = $username;
                    $_SESSION['pwd'] = $pwd;
                    $_SESSION['role'] = "admin";
                    $_SESSION['encrypt_method'] = 'DES-ECB';
                    $_SESSION['encrypt_passwd'] = '*Y7RHKdn8fwYwDTK8m3EJp%bi57FuyRiA&5FkU$JpO%BNCVMAD';

                    header("Location: ../index.php?login=success&role=admin");
                    exit();

                        //if other
                } else {
                    header("Location: ../login.php?error=wrong&role=admin");
                    exit();
                }
            }
        } else {
            header("Location: ../login.php?role=admin");
            exit();
        }
    } else {
        header("Location: ../index.php");
        exit();
    }

}

ob_end_flush();