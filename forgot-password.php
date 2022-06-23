<?php

include('connections/config.php');

//requirements para makapagsend ng email
require('assets/PHPMailer-master/src/PHPMailer.php');
require('assets/PHPMailer-master/src/SMTP.php');
require('assets/PHPMailer-master/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

error_reporting(0);

//if user click continue button in forgot password form
if (isset($_POST['submit'])) {

    try {
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        $sql = "SELECT * FROM staffs WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $count  = mysqli_num_rows($result);

        if ($count > 0) {

            $code = rand(999999, 111111);

            $sql = "UPDATE staffs SET otp = '$code' WHERE email = '$email'";
            $result =  mysqli_query($conn, $sql);

            if ($result == TRUE) {

                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->SMTPAuth = "true";
                $mail->SMTPSecure = "tls";
                $mail->Port = "587";
                $mail->Username = "contrivekcs@gmail.com"; //ito yung gamit kong email pang send
                $mail->Password = "contrivekcs"; //password ng email ko
                $mail->Subject = "Password Reset Code"; //subject ng email natin
                $mail->setFrom("contrivekcs@gmail.com"); //kung kanino galing yung email
                $mail->isHTML(true); //naka true para madesignan yung email body
                $mail->Body = "Your Password Reset Code Is $code"; // dito sa body pwede mo designan ng html at css yung body ng email natin
                $mail->addAddress($email); //kung kanino isesend yung email, wala nang problema dito

                if ($mail->Send()) {

                    $_SESSION['email-success'] = "We've Sent A Password Reset Code To Your Email - $email";
                    $_SESSION['email'] = $email;
                    $_POST['email'] = "";
                    header("Location: code-verification.php");
                } else {

                    $_SESSION['failed-to-email'] = "Failed While Sending Code! Please Try Again Later.";
                }
                $mail->smtpClose();
            } else {

                $_SESSION['failed-to-email'] = "Something Went Wrong! Please Try Again Later";
            }
        } else {

            $_SESSION['email-not-found'] = "Email Address Not Found!";
        }
    } catch (\Throwable $e) {
        $e->getMessage();
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
    <link rel="shortcut icon" href="assets/images/icon.ico">
    <title>CONTRIVE | Forgot Password</title>
</head>

<body>
    <!-- Login/Register -->
    <div class="container">

        <div class="card col-lg-5 col-md-6 col-12 mx-auto px-3 py-4">
            <form method="post">
                <div class="row justify-content-center mb-1">
                    <img class="w-50 p-3" src="assets/images/contrive.png">
                </div>
                <?php
                if (isset($_SESSION['failed-to-email'])) {
                ?>
                    <p class="text-center mb-4 alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-email']; ?> </strong>
                    </p>
                <?php
                    unset($_SESSION['failed-to-email']);
                }
                if (isset($_SESSION['email-not-found'])) {
                ?>
                    <p class="text-center mb-4 alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['email-not-found']; ?> </strong>
                    </p>
                <?php
                    unset($_SESSION['email-not-found']);
                }
                ?>
                <h4 class="text-center">Forgot your Password?</h4>
                <p class="text-center mb-4">Enter your email address that you used to register. <br> We'll send you an email with your username and a link to reset your password.</p>
                <div class="form-group d-flex px-3 py-1 m-0">
                    <i class="fas fa-envelope my-auto"></i>
                    <input type="email" name="email" autocomplete="off" class="form-control" id="email" required placeholder="Email Address" value="<?php echo $_POST['email']; ?>" />
                </div>
                <p><small id="user_error_msg" class="text-danger"></small></p>
                <div class="text-center pt-1">
                    <button type="submit" name="submit" id="submit_btn" class="btn btn-primary">Send Password Reset Link</button></br>
                </div>
                <div class="text-center pt-4">
                    <a href="index.php" style="font-size: 15px">Back to Log In</a>
                </div>
            </form>
        </div>
    </div>

    <!-- End of Login/Register -->
</body>

</html>