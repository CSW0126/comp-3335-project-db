<?php
session_start();
//check submit button
if (isset($_POST['submit'])) {
    require '../connection/mysqli_conn.php';

    $customerEmail = $_SESSION['Email'];
    $firstName = $_POST['fname'];
    $lastName = $_POST['lname'];
    $password = $_POST['pw'];
    $passwordRepeat = $_POST['cpw'];
    $phoneNumber = $_POST['phone'];

    //check empty fields
    if (empty($firstName) || empty($lastName) || empty($password) || empty($passwordRepeat) || empty($phoneNumber)) {
        header("Location: ../editCustomerProfile.php?error=emptyfields&email=" . $customerEmail .
            "&fname=" . $fname .
            "&lname=" . $lastName .
            "&phone=" . $phoneNumber);
        exit();
        //check all   
    } else if (((!preg_match("/^[a-zA-Z0-9]{3,255}$/", $firstName)) && (!preg_match("/^[a-zA-Z0-9]{3,255}$/", $lastName))) && !preg_match("/^[25689]{1}\d{7}$/", $phoneNumber)) {
        header("Location: ../editCustomerProfile.php?error=invalidNamePhone");
        exit();

        //check name 
    } else if ((!preg_match("/^[a-zA-Z0-9]{3,255}$/", $firstName)) || (!preg_match("/^[a-zA-Z0-9]{3,255}$/", $lastName))) {
        header("Location: ../editCustomerProfile.php?error=invalidname&email=" . $customerEmail .
            "&phone=" . $phoneNumber);
        exit();

        //check phone 
    } else if (!preg_match("/^[25689]{1}\d{7}$/", $phoneNumber)) {
        header("Location: ../editCustomerProfile.php?error=invalidphone&email=" . $customerEmail .
            "&fname=" . $fname .
            "&lname=" . $lastName);

        //check password
    } else if ($password !== $passwordRepeat) {
        header("Location: ../editCustomerProfile.php?error=invalidpassword&email=" . $customerEmail .
            "&fname=" . $fname .
            "&lname=" . $lastName .
            "&phone=" . $phoneNumber);
        exit();
        //form validated
    } else {
        //check phone other than the user
        $sql = "SELECT * FROM customer WHERE phoneNumber = ? AND customerEmail != ?";
        $stmt = mysqli_stmt_init($conn);
        //if sql not valid
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../editCustomerProfile.php?error=sqlError");
            exit();
            //if sql valid check if duplicate phone
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $phoneNumber,$customerEmail);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);

            //if phone exists
            if ($resultCheck > 0) {
                header("Location: ../editCustomerProfile.php?error=phoneTaken&phone=" . $phoneNumber .
                    "&fname=" . $fname .
                    "&lname=" . $lastName .
                    "&email=" . $customerEmail);
                exit();

                //if pass, update data
            } else {

                $sql = "UPDATE customer SET firstName = ?, lastName = ?, password = ?, phoneNumber = ? WHERE customerEmail = ?";
                $stmt = mysqli_stmt_init($conn);
                //if sql not valid
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../editCustomerProfile.php?error=sqlError");
                    exit();
                //update
                } else {
                    //hash encrypt
                    //$hashPwd = password_hash($password, PASSWORD_DEFAULT);
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "sssss",  $firstName, $lastName, $password, $phoneNumber, $customerEmail);
                    mysqli_stmt_execute($stmt);
                    //update session
                    session_start();
                    $_SESSION['firstname'] = $firstName;
                    $_SESSION['lastname'] = $lastName;
                    $_SESSION['phoneNumber'] = $phoneNumber;
                    header("Location: ../editCustomerProfile.php?update=success");
                    exit();
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    header("Location: ../editCustomerProfile.php");
    exit();
}
