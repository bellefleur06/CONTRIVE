<?php

include('connections/config.php');

$email = $_SESSION['email'];

//check if the page is not forcefully accessed
if ($email == FALSE) {
    header("Location: index.php");
}

// error_reporting(0);

//if user click check reset otp button
if (isset($_POST['submit'])) {

    $code = mysqli_real_escape_string($conn, $_POST['code']);

    $sql = "SELECT * FROM staffs WHERE otp = '$code'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $count = mysqli_num_rows($result);

    if ($count > 0) {

        $_SESSION['email'] = $row['email'];

        $_SESSION['code-verification-successful'] = "Please Create A New Password That You Don't Use On Any Other Site.";
        header("Location: reset-password.php");
    } else {

        $_SESSION['code-verification-failed'] = "You've Entered Incorrect Code!";
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
    <title>CONTRIVE | Code Verification</title>
</head>

<body>
    <!-- Login/Register -->
    <div class="container">

        <div class="card col-lg-5 col-md-6 col-12 mx-auto px-3 py-4">
            <form method="post">
                <div class="row justify-content-center mb-4">
                    <img class="w-25" src="assets/images/icon.ico">
                </div>
                <h4 class="text-center mb-4">Code Verification</h4>
                <?php
                if (isset($_SESSION['email-success'])) {
                ?>
                    <p class="text-center mb-4 alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['email-success']; ?> </strong>
                    </p>
                <?php
                    unset($_SESSION['email-success']);
                }
                if (isset($_SESSION['code-verification-failed'])) {
                ?>
                    <p class="text-center mb-4 alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['code-verification-failed']; ?> </strong>
                    </p>
                <?php
                    unset($_SESSION['code-verification-failed']);
                }
                ?>
                <div class="form-group d-flex px-3 py-1 m-0">
                    <i class="fas fa-envelope my-auto"></i>
                    <input type="text" name="code" autocomplete="off" class="form-control" id="code" placeholder="Enter Reset Code" />
                </div>
                <p><small id="user_error_msg" class="text-danger"></small></p>
                <div class="text-center pt-1">
                    <button type="submit" name="submit" id="submit_btn" class="btn btn-primary col-4">Submit</button></br>
                </div>
            </form>
        </div>
    </div>

    <!-- End of Login/Register -->
</body>

</html>