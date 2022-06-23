<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$sql = "SELECT * FROM staffs WHERE id = '{$_SESSION['id']}'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Manage Account</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <link rel="stylesheet" href="../assets/css/style.css" />

</head>

<body class="app">

    <?php include('accountant-navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <ol class="breadcrumb mb-2" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item">Manage Account</li>
                </ol>
                <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-user"></i></span> "<?php echo $row['full_name']; ?>" Profile</h1>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['account-not-found'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong> <?php echo $_SESSION['account-not-found']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['account-not-found']);
                }
                ?>
                <div class="row g-4 mb-4">
                    <div class="col-auto">
                        <div class="app-card shadow-sm ">
                            <div class="row">
                                <img src="../staff_images/<?php echo $row['profile']; ?>" alt="" style="width:250px;height:250px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table class="table app-table-hover text-left">
                                            <!-- <tr>
                                        <center>
                                            <img src="../staff_images/<?php echo $row['profile']; ?>" alt="" style="width:300px;height:300px; border-radius:50%;">
                                        </center>
                                    </tr> -->
                                            <tr>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label">Full Name: </label>
                                                </td>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['full_name']; ?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label">Birthday: </label>
                                                </td>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label" style="color:#363636">
                                                        <?php
                                                        $birthday = $row['birthday'];
                                                        echo $date = date("M d, Y", strtotime($birthday));
                                                        ?>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label">Gender: </label>
                                                </td>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['gender']; ?></label>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label">Address: </label>
                                                </td>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['address']; ?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label">Contact No.: </label>
                                                </td>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['contact']; ?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label">Civil Status: </label>
                                                </td>
                                                <td class="cell">
                                                    <label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['civil_status']; ?></label>
                                                </td>
                                            </tr>
                                        </table>
                                        <form class="settings-form" method="post">
                                            <a href="update-account.php?ID=<?php echo $row['id']; ?>" class="btn app-btn-primary"><i class="fa fa-edit"></i> Edit Profile</a>
                                            <!-- <a href="change-password.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-danger" style="color:white; float:right"><i class="fa fa-lock"></i> Change Password</a> -->
                                        </form>
                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>

                        </div>
                        <!--//row-->
                    </div>
                </div>



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

</html>