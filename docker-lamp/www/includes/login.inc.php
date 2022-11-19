<?php
require_once "../connection/mysqli_conn.php";

if (isset($_POST['role'])) {
    $role = $_POST['role'];
    //customer login
    if ($role == "customer") {
        if (isset($_POST['login'])) {

            $customerEmail = $_POST['email'];
            $password = $_POST['pwd'];


            //check empty field
            if (empty($customerEmail) || empty($password)) {
                header("Location: ../login.php?error=emptyfields&role=customer");
                exit();
            } else {
                $sql = "SELECT * FROM customer WHERE customerEmail = ?";
                $stmt = mysqli_stmt_init($conn);

                //check sql valid
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../login.php?error=sqlerror");
                    exit();
                    //if valid
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $customerEmail);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    //get row
                    if ($row = mysqli_fetch_assoc($result)) {
                        $pwdCheck = password_verify ($password,$row['password']);
                        //if false
                        if ($pwdCheck == false) {
                            header("Location: ../login.php?error=wrong&role=customer");
                            exit();
                            //if true
                        } else {
                            session_start();
                            $_SESSION['Email'] = $row['customerEmail'];
                            $_SESSION['firstname'] = $row['firstName'];
                            $_SESSION['lastname'] = $row['lastName'];
                            $_SESSION['phoneNumber'] = $row['phoneNumber'];
                            $_SESSION['role'] = "customer";
                            $_SESSION['encrypt_method'] = 'DES-ECB';
                            $_SESSION['encrypt_passwd'] = '*Y7RHKdn8fwYwDTK8m3EJp%bi57FuyRiA&5FkU$JpO%BNCVMAD';
                            header("Location: ../index.php?login=success&role=customer");
                            exit();

                            //if other
                        }
                    } else {
                        header("Location: ../login.php?error=wrong&role=customer");
                        exit();
                    }
                }
            }
        } else {
            header("Location: ../login.php?role=customer");
            exit();
        }
        //tenant login   
    } else if ($role == "tenant") {
        if (isset($_POST['login'])) {

            $tid = $_POST['tid'];
            $password = $_POST['pwd'];

            //check empty field
            if (empty($tid) || empty($password)) {
                header("Location: ../login.php?error=emptyfields&role=tenant");
                exit();
            } else {
                $sql = "SELECT * FROM tenant WHERE tenantID = ?";
                $stmt = mysqli_stmt_init($conn);

                //check sql valid
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../login.php?error=sqlerror");
                    exit();
                    //if valid
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $tid);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    //get row
                    if ($row = mysqli_fetch_assoc($result)) {
                        $pwdCheck = password_verify($password,$row['password']);
                        //if false
                        if ($pwdCheck == false) {
                            header("Location: ../login.php?error=wrong&role=tenant");
                            exit();
                            //if true
                        } else {
                            session_start();
                            $_SESSION['tid'] = $row['tenantID'];
                            $_SESSION['name'] = $row['name'];
                            $_SESSION['role'] = "tenant";
                            //get store ID
                            $stmtGetStoreID = $conn->prepare("SELECT * FROM consignmentstore where tenantID = ?");
                            $stmtGetStoreID->bind_param("s", $row['tenantID']);
                            $stmtGetStoreID->execute();
                            $storeIDResult = $stmtGetStoreID->get_result();
                            while ($store = mysqli_fetch_assoc($storeIDResult)){
                                $storeID[] = $store['consignmentStoreID'];
                            }

                            $_SESSION['storeID'] = $storeID;
                            $_SESSION['encrypt_method'] = 'DES-ECB';
                            $_SESSION['encrypt_passwd'] = '*Y7RHKdn8fwYwDTK8m3EJp%bi57FuyRiA&5FkU$JpO%BNCVMAD';

                            header("Location: ../index.php?login=success&role=tenant");
                            exit();

                            //if other
                        }
                    } else {
                        header("Location: ../login.php?error=wrong&role=tenant");
                        exit();
                    }
                }
            }
        } else {
            header("Location: ../login.php?role=tenant");
            exit();
        }

        //else go to index
    } else {
        header("Location: ../index.php");
        exit();
    }
}
