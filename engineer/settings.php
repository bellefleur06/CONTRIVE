<?php

include('../connections/config.php');

//check if the user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}
$sql = "SELECT * FROM staffs WHERE id = '{$_SESSION['id']}'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {

    $id = mysqli_real_escape_string($conn, $_POST['ID']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "UPDATE staffs SET username = '$username', email = '$email' WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result = TRUE) {

        $_SESSION['update-account'] = "Account Credentials Updated Successfully!";

        $sql = "SELECT * FROM staffs WHERE id = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['failed-to-update'] = "Failed To Update Account Credentials.";
    }
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Settings</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link id="theme-style" rel="stylesheet" href="dataTables/jquery.dataTables.min.css">
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <link rel="stylesheet" href="../assets/css/style.css" />

    <!-- JQuery -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>

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
                <ol class="breadcrumb mb-2" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">User Settings</li>
                </ol>
                <h1 class="app-page-title"><span class="nav-icon"></span><i class="fas fa-user"></i> User Settings</h1>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['update-account'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['update-account']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['update-account']);
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
                ?>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h3 class="section-title">Account Information</h3>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="app-card app-card-settings shadow-sm p-4">

                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" id="setting-input-1" autocomplete="off" value="<?php echo $row['username']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="setting-input-1" autocomplete="off" value="<?php echo $row['email']; ?>" required>
                                    </div>
                                    <div class="mb-3">

                                    </div>
                                    <a href="change-password.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-danger" style="color:white;float:right"><i class="fa fa-lock"></i> Change Password</a>
                                    <input type="hidden" name="ID" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="submit" class="btn app-btn-primary">Save Changes</button> &nbsp;
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
        </div>

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