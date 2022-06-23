<?php

include('connections/config.php');

error_reporting(0);

$email = $_SESSION['email'];

//check if the page is not forcefully accessed
if ($email == FALSE) {
    header("Location: index.php");
}

//if user click change password button
if (isset($_POST['submit'])) {

    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));

    //check if password uses alphanumeric characters
    if (ctype_alnum($password)) {

        //check if password is 8 or more characters long
        if (strlen($password) >= 8) {

            //check if both inputed password are the same
            if ($password == $confirm_password) {

                $code = 0;
                $email = $_SESSION['email'];

                $sql = "UPDATE staffs SET otp = $code, password = '$password' WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);

                if ($result == TRUE) {

                    $_SESSION['password-reset-successful'] = "Password Reset Successful! You Can Now Login With Your New Password.";
                    header("Location: index.php");
                } else {

                    $_SESSION['password-reset-failed'] = "Failed To Reset Password! Please Try Again Later.";
                }
            } else {

                $_SESSION['password-not-matched'] = "Password Not Matched.";
            }
        } else {
            $_SESSION['invalid-password'] = "Invalid Password! Password Must Be Atleast 8 Characters Long And Contains Atleast 1 Uppercase Letter, 1 Lowercase Letter and A Number.";
        }
    } else {
        $_SESSION['invalid-password'] = "Invalid Password! Password Must Be Atleast 8 Characters Long And Contains Atleast 1 Uppercase Letter, 1 Lowercase Letter and A Number.";
    }
}


?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/login.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <title>CONTRIVE | Reset Password</title>
</head>

<body>
    <!-- Login -->
    <div class="container">
        <div class="card col-lg-5 col-md-6 col-12 mx-auto px-3 py-4">
            <form method="post">
                <div class="row justify-content-center mb-4">
                    <img class="w-25" src="assets/images/icon.ico">
                </div>
                <h4 class="text-center mb-4">Reset Password</h4>
                <?php
                if (isset($_SESSION['code-verification-successful'])) {
                ?>
                    <p class="text-center mb-4 alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['code-verification-successful']; ?> </strong>
                    </p>
                <?php
                    unset($_SESSION['code-verification-successful']);
                }
                if (isset($_SESSION['password-reset-failed'])) {
                ?>
                    <p class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['password-reset-failed']; ?> </strong>
                    </p>
                <?php
                    unset($_SESSION['password-reset-failed']);
                }
                if (isset($_SESSION['password-not-matched'])) {
                ?>
                    <p class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['password-not-matched']; ?> </strong>
                    </p>
                <?php
                    unset($_SESSION['password-not-matched']);
                }
                if (isset($_SESSION['invalid-password'])) {
                ?>
                    <p class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['invalid-password']; ?> </strong>
                    </p>
                <?php
                    unset($_SESSION['invalid-password']);
                }
                ?>
                <div class="form-group d-flex px-3 py-1 m-0 mb-4">
                    <i class="fas fa-unlock-alt my-auto"></i>
                    <input type="password" name="password" autocomplete="off" class="form-control" id="password" placeholder="Enter New Password" required value="<?php echo $_POST['password']; ?>" />
                </div>
                <p><small id="user_error_msg" class="text-danger"></small></p>
                <div class="form-group d-flex px-3 py-1 m-0 mb-4">
                    <i class="fas fa-unlock-alt my-auto"></i>
                    <input type="password" name="confirm_password" autocomplete="off" class="form-control" id="password" placeholder="Confirm New Password" required value="<?php echo $_POST['confirm_password']; ?>" />
                </div>
                <p><small id="pass_error_msg" class="text-danger"></small></p>
                <div class="text-center pt-1">
                    <button type="submit" name="submit" id="submit_btn" class="btn btn-primary col-4">Submit</button></br>
                </div>
            </form>
        </div>
    </div>
    <!-- End of Login -->
</body>

<script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", function() {
        //toggle the type attribute
        const type =
            password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);
        //toggle the type attribute
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
</script>

</html>