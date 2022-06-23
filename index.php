<?php

include('connections/config.php');

error_reporting(0);

//check if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: admin/dashboard.php");
}

//check if 10 secs had past
if (isset($_SESSION['locked'])) {

    $difference = time() - $_SESSION['locked'];

    if ($difference >= 5) {

        unset($_SESSION['locked']);
        unset($_SESSION['login-attempts']);
    }
}

//check if login button is clicked
if (isset($_POST['submit'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));

    $sql = "SELECT * FROM staffs WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $count = mysqli_num_rows($result);

    //check if user record is existing in db
    if ($count == 1) {

        $_SESSION['username'] = $username;
        $_SESSION['id'] = $row['id'];
        $_SESSION['access'] = $row['access'];
        $_SESSION['profile'] = $row['profile'];

        $sql = "UPDATE staffs SET last_login = now() WHERE id = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);

        //update last login date and time
        if ($result == TRUE) {
        } else {
            echo "<script>alert('Error in Updating Last Login')</script>";
        }

        $sql = "UPDATE staffs SET last_activity = now() WHERE id = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);

        //update last activity date and time
        if ($result = TRUE) {
        } else {
            echo "<script>alert('Error in Updating Last Activity')</script>";
        }

        //check if remember me checkbox is checked
        if (!empty($_POST['remember'])) {

            $remember = $_POST['remember'];

            //Set Cookie
            setcookie('username', $_POST['username'], time() + (10 * 365 * 24 * 60 * 60));
            setcookie('password', $_POST['password'], time() + (10 * 365 * 24 * 60 * 60));
        } else {

            //Expire Cookie
            if (isset($_COOKIE['username'])) {

                setcookie('username', '');
            }
            if (isset($_COOKIE['password'])) {

                setcookie('password', '');
            }
        }

        //check if user is logged in as admin 
        if ($_SESSION['access'] == "Admin") {

            echo "<script>window.location.replace('admin/dashboard.php');</script>";

            //clear textboxes if log in is successful
            $_POST['username'] = "";
            $_POST['password'] = "";
        } else if ($_SESSION['access'] == "Accountant") {

            echo "<script>window.location.replace('accountant/dashboard.php');</script>";

            $_POST['username'] = "";
            $_POST['password'] = "";
        } else {

            echo "<script>window.location.replace('engineer/dashboard.php');</script>";

            $_POST['username'] = "";
            $_POST['password'] = "";
        }
    } else {

        echo "<script>alert('You Only Have 3 Attempts to Login Your Correct Credentials.')</script>";
        $_SESSION['login-attempts'] += 1;
        $_SESSION['message'] = "You Have Been Locked! Please Wait For 30 Seconds Then Refresh The Page To Login Again.";
        $_SESSION['login'] = "Invalid Username or Password!";
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
    <title>CONTRIVE | Log In</title>
</head>

<body>
    <!-- Login -->
    <div class="container">

        <div class="card col-lg-5 col-md-6 col-12 mx-auto px-3 py-4">
            <form method="post">
                <div class="row justify-content-center mb-4">
                    <img class="w-50 p-3" src="assets/images/contrive.png">
                </div>
                <?php
                if (isset($_SESSION['login'])) {
                ?>
                    <p class="text-center mb-4 alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['login']; ?> </strong>
                    </p>
                <?php
                    unset($_SESSION['login']);
                }
                if (isset($_SESSION['password-reset-successful'])) {
                ?>
                    <p class="text-center mb-4 alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['password-reset-successful']; ?> </strong>
                    </p>
                <?php
                    unset($_SESSION['password-reset-successful']);
                }
                ?>
                <h4 class="text-center ">Welcome back!</h4>
                <p class="text-center mb-4">Sign in to your account to continue</p>
                <div class="form-group d-flex px-3 py-1 m-0">
                    <i class="fas fa-user my-auto"></i>
                    <input type="text" name="username" autocomplete="off" class="form-control" id="username" placeholder="Username" required value="<?php if (isset($_COOKIE['username'])) {
                                                                                                                                                        echo $_COOKIE['username'];
                                                                                                                                                    }; ?>" />
                </div>
                <p><small id="user_error_msg" class="text-danger"></small></p>
                <div class="form-group d-flex px-3 py-1 m-0 mb-4">
                    <i class="fas fa-unlock-alt my-auto"></i>
                    <input type="password" name="password" autocomplete="off" class="form-control" id="password" placeholder="Password" required value="<?php if (isset($_COOKIE['password'])) {
                                                                                                                                                            echo $_COOKIE['password'];
                                                                                                                                                        }; ?>" />
                    <span class="input-group-text" style="background-color: white; border:none"><i class="far fa-eye" id="togglePassword" style="cursor:pointer"></i></span>
                </div>
                <p><small id="pass_error_msg" class="text-danger"></small></p>
                <div class="mb-4 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember" <?php if (isset($_COOKIE['username'])) {
                                                                                                    ?> checked <?php
                                                                                                            } ?>>
                    <label class="form-check-label" for="remember">Remember me</label>
                    <a href="forgot-password.php" style="float: right; font-size: 14px;">Forgot your password?</a>
                </div>
                <div class="text-center pt-1">
                    <?php
                    if ($_SESSION['login-attempts'] > 2) {
                        $_SESSION['locked'] =  time();
                    ?>
                        <p class="text-center mb-4 alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                            <strong> <?php echo $_SESSION['message']; ?> </strong>
                        </p>
                    <?php
                    } else {
                    ?>
                        <button type="submit" name="submit" id="submit_btn" class="btn btn-primary col-4">Log In</button></br>
                    <?php
                    }
                    ?>
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

<script>
    setTimeout(function() {
        document.getElementById("alert").style.display = "none";
    }, 5000);
</script>

</html>