<?php
//check submit button
if (isset($_POST['submit'])) {
    require '../connection/mysqli_conn.php';

    $customerEmail = $_POST['email'];
    $firstName = $_POST['fname'];
    $lastName = $_POST['lname'];
    $password = $_POST['pw'];
    $passwordRepeat = $_POST['cpw'];
    $phoneNumber = $_POST['phone'];

    //check empty fields
    if (empty($customerEmail) || empty($firstName) || empty($lastName) || empty($password) || empty($passwordRepeat) || empty($phoneNumber)) {
        header("Location: ../signup.php?error=emptyfields");
        exit();
    //check all   
    } else if ((!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) && ((!preg_match("/^[a-zA-Z0-9]{3,255}$/", $firstName)) && (!preg_match("/^[a-zA-Z0-9]{3,255}$/", $lastName))) && !preg_match("/^[25689]{1}\d{7}$/", $phoneNumber)) {
        header("Location: ../signup.php?error=invalidNameMailPhone");
        exit();
    }

    //check valid email
    else if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../signup.php?error=invalidmail");
        exit();

        //check name 
    } else if ((!preg_match("/^[a-zA-Z0-9]{3,255}$/", $firstName)) || (!preg_match("/^[a-zA-Z0-9]{3,255}$/", $lastName))) {
        header("Location: ../signup.php?error=invalidname");
        exit();

        //check phone 
    } else if (!preg_match("/^[25689]{1}\d{7}$/", $phoneNumber)) {
        header("Location: ../signup.php?error=invalidphone");

        //check password
    } else if ($password !== $passwordRepeat) {
        header("Location: ../signup.php?error=invalidpassword");
        exit();
    } else if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password)) {
        header("Location: ../signup.php?error=pwdnotstrong");
        exit();
        //form validated
    } else {
        //check email sql
        $sql = "SELECT * FROM customer WHERE customerEmail = ?";
        $stmt = mysqli_stmt_init($conn);

        //if sql not valid
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../signup.php?error=sqlError");
            exit();
            //if sql valid check if duplicate email
        } else {
            mysqli_stmt_bind_param($stmt, "s", $customerEmail);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);

            //if email exists
            if ($resultCheck > 0) {
                header("Location: ../signup.php?error=emailTaken&email=" . $customerEmail .
                    "&fname=" . $fname .
                    "&lname=" . $lastName .
                    "&phone=" . $phoneNumber);
                exit();
                //pass email check
            } else {
                //phone email sql
                $sql = "SELECT * FROM customer WHERE phoneNumber = ?";
                $stmt = mysqli_stmt_init($conn);

                //if sql not valid
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../signup.php?error=sqlError");
                    exit();
                    //if sql valid check if duplicate phone
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $phoneNumber);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $resultCheck = mysqli_stmt_num_rows($stmt);

                    //if phone exists
                    if ($resultCheck > 0) {
                        header("Location: ../signup.php?error=phoneTaken&phone=" . $phoneNumber .
                            "&fname=" . $fname .
                            "&lname=" . $lastName .
                            "&email=" . $customerEmail);
                        exit();

                        //if pass, insert data
                    } else {

                        $sql = "INSERT INTO customer (customerEmail,firstName,lastName,password,phoneNumber) VALUE (? , ? , ? , ? , ?)";
                        $stmt = mysqli_stmt_init($conn);
                        //if sql validated
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            header("Location: ../signup.php?error=sqlError");
                            exit();
                        } else {
                            //hash password
                            $password = password_hash($password, PASSWORD_DEFAULT);
                            mysqli_stmt_bind_param($stmt, "sssss", $customerEmail, $firstName, $lastName, $password, $phoneNumber);
                            mysqli_stmt_execute($stmt);
                            header("Location: ../signup.php?signup=success");
                            exit();
                        }
                    }
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: ../signup.php");
    exit();
}
