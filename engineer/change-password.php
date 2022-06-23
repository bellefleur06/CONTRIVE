<?php

error_reporting(0);

include('../connections/config.php');

//check is user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM staffs WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

//check if there are account records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if account exist
    if ($count > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
        }
    } else {

        $_SESSION['account-not-found'] = "Account Not Found.";
        header("Location: manage-account.php");
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['account-not-found'] = "Account Not Found.";
    header("Location: manage-account.php");
}

if (isset($_POST['submit'])) {

    $old_password = mysqli_real_escape_string($conn, md5($_POST['old_password']));
    $new_password = mysqli_real_escape_string($conn, md5($_POST['new_password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));
    $id = mysqli_real_escape_string($conn, $_POST['ID']);
    $activity = "Update Account Password";

    //check if password uses alphanumeric characters
    if (ctype_alnum($new_password)) {

        //check if password is 8 or more characters long
        if (strlen($new_password) >= 8) {

            $sql = "SELECT * FROM staffs WHERE id = $id AND password ='$old_password'";
            $result = mysqli_query($conn, $sql);

            //check if there are staff records
            if ($result == TRUE) {

                $count = mysqli_num_rows($result);

                //check if staff exist
                if ($count == 1) {

                    //check if both inputed password are the same
                    if ($new_password == $confirm_password) {

                        $sql = "UPDATE staffs SET password = '$new_password' WHERE id = $id";
                        $result = mysqli_query($conn, $sql);

                        //check if the update process is true
                        if ($result == TRUE) {

                            $sql = "UPDATE staffs SET last_activity = now() WHERE id = '{$_SESSION['id']}'";
                            $result = mysqli_query($conn, $sql);

                            //update last activity date and time
                            if ($result = TRUE) {
                            } else {
                                echo "<script>alert('Error in Updating Last Activity')</script>";
                            }

                            $sql = "INSERT INTO logs SET username = '{$_SESSION['username']}' , log_time = now(), activity = '$activity'";
                            $result = mysqli_query($conn, $sql);

                            //insert info into audit trail
                            if ($result = TRUE) {
                            } else {
                                echo "<script>alert('Error in Recording Logs')</script>";
                            }

                            $_POST['old_password'] = "";
                            $_POST['new_password'] = "";
                            $_POST['confirm_password'] = "";

                            $_SESSION['update-password'] = "Password Updated Successfully!";
                        } else {

                            $_SESSION['failed-to-update'] = "Failed to Update Password.";
                        }
                    } else {

                        $_SESSION['password-not-matched'] = "Password Not Matched.";
                    }
                } else {

                    $_SESSION['incorrect-old-password'] = "Incorrect Old Password.";
                }
            } else {

                $_SESSION['staff-not-found'] = "Staff Not Found.";
            }
        } else {

            $_SESSION['invalid-password'] = "Invalid Password! Password Must Be Aleast 8 Characters Long And Must Consists Of An Uppercase Letter, A Lowercase Letter And A Number.";
        }
    } else {

        $_SESSION['invalid-password'] = "Invalid Password! Password Must Be Aleast 8 Characters Long And Must Consists Of An Uppercase Letter, A Lowercase Letter And A Number.";
    }
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Change Password</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

    <!-- used to prevent data to insert again when page is refreshed-->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>

<body class="app">

    <?php include('engineer-navbar.php'); ?>

    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-lock"></i></span> Change Password</h1>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['update-password'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['update-password']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['update-password']);
                }
                if (isset($_SESSION['failed-to-update'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-update']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['failed-to-update']);
                }
                if (isset($_SESSION['password-not-matched'])) {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['password-not-matched']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['password-not-matched']);
                }
                if (isset($_SESSION['incorrect-old-password'])) {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['incorrect-old-password']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['incorrect-old-password']);
                }
                if (isset($_SESSION['invalid-password'])) {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['invalid-password']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['invalid-password']);
                }
                ?>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">
                        <div class="app-card app-card-settings shadow-sm p-4">

                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Old Password</label>
                                        <input type="password" name="old_password" class="form-control" id="setting-input-3" autocomplete="off" value="<?php echo $_POST['old_password']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">New Password</label>
                                        <input type="password" name="new_password" class="form-control" id="setting-input-3" autocomplete="off" placeholder="Password Must Be Aleast 8 Characters Long And Must Consists Of An Uppercase Letter, A Lowercase Letter And A Number" value="<?php echo $_POST['new_password']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control" id="setting-input-3" autocomplete="off" value="<?php echo $_POST['confirm_password']; ?>" required>
                                    </div>
                                    <input type="hidden" name="ID" value="<?php echo $id; ?>">
                                    <button type="submit" name="submit" class="btn app-btn-primary">Save</button>
                                    <a href="settings.php?ID=<?php echo $id; ?>" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
                                </form>
                            </div>
                            <!--//app-card-body-->

                        </div>
                        <!--//app-card-->
                    </div>
                </div>
                <!--//row-->
                <hr class="my-4">
            </div>
            <!--//container-fluid-->
        </div>
        <!--//app-content-->
    </div>
    <!--//app-wrapper-->

    <!-- Javascript -->
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script>

</body>

<script>
    setTimeout(function() {
        document.getElementById("alert").style.display = "none";
    }, 3000);
</script>

</html>