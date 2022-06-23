<?php

include('../connections/config.php');

error_reporting(0);

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = 0;
$edit = false;

if (isset($_POST['submit'])) {

    $position =  mysqli_real_escape_string($conn, $_POST['position']);
    $rate =  mysqli_real_escape_string($conn, $_POST['rate']);
    $activity = "Add New Worker Position - " . $position;

    $sql =  "SELECT position FROM positions WHERE position = '$position'";
    $result = mysqli_query($conn, $sql);

    //check if client already exist
    if (!$result->num_rows > 0) {

        $sql = "INSERT INTO positions (position, rate) VALUES ('$position', '$rate')";
        $result = mysqli_query($conn, $sql);

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

            $_SESSION['add-positions'] = "Position Added Successfully!";

            //clear texboxes if the result is true
            $_POST['position'] = "";
            $_POST['rate'] = "";
        } else {
            $_SESSION['failed-to-add'] = "Failed to Add Position.";
        }
    } else {
        $_SESSION['position-already-exist'] = "Position Already Exist.";
    }
}

//edit button
if (isset($_GET['ID'])) {

    $position_id = $_GET['ID'];
    $edit = true;

    $sql = "SELECT * FROM positions WHERE id = $position_id;";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($result == TRUE) {

        $count = mysqli_num_rows($result);

        if ($count == 1) {
            $position = $row['position'];
            $rate = $row['rate'];
        }
    }
}

//check if update button is clicked
if (isset($_POST['update'])) {

    $position =  mysqli_real_escape_string($conn, $_POST['position']);
    $rate = mysqli_real_escape_string($conn, $_POST['rate']);
    $activity = "Update Position Details For " . $position;

    $sql = "UPDATE positions SET position = '$position', rate = '$rate' WHERE id = $position_id";
    $result = mysqli_query($conn, $sql);

    //check if update process if true
    if ($result == TRUE) {

        $sql = "UPDATE workers SET position = '$position', rate = '$rate' WHERE position_id = $position_id";
        $result = mysqli_query($conn, $sql);

        //update last activity date and time
        if ($result = TRUE) {
        } else {
            echo "<script>alert('Error in Updating Last Activity')</script>";
        }

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

        $_SESSION['update-positions'] = "Position Details Updated Successfully!";
    } else {

        $_SESSION['failed-to-update'] = "Failed to Update Client Details.";
    }
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Manage Worker Positions</title>

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

    <!-- used to prevent data to insert again when page is refreshed-->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>

<body class="app">

    <?php $page = 'worker';
    include('navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="manage-workers.php">Manage Workers</a></li>
                    <li class="breadcrumb-item active">Manage Worker Positions</li>
                </ol>
                <h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Manage Worker Positions</h1>
                <a href="manage-workers.php" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-list"></i> Worker List</a>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['add-positions'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['add-positions']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['add-positions']);
                }
                if (isset($_SESSION['failed-to-add'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-add']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['failed-to-add']);
                }
                if (isset($_SESSION['failed-to-update'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-update']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['failed-to-update']);
                }
                if (isset($_SESSION['position-already-exist'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['position-already-exist']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['position-already-exist']);
                }
                if (isset($_SESSION['position-not-found'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['position-not-found']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['position-not-found']);
                }
                if (isset($_SESSION['update-positions'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['update-positions']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['update-positions']);
                }
                ?>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <?php if ($edit == true) : ?>
                            <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-user"></i></span> Update Position</h1>
                        <?php else : ?>
                            <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-user"></i></span> Add Positions</h1>
                        <?php endif ?>

                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <div class="mb-3">
                                        <label for="setting-input-2" class="form-label">Position:</label>
                                        <input type="text" name="position" class="form-control" <?php if ($edit == true) : ?> value="<?php echo $position; ?>" <?php endif ?> id="setting-input-2" autocomplete="off" value="<?php echo $_POST['position']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-2" class="form-label">Hourly Rate:</label>
                                        <input type="number" name="rate" class="form-control" <?php if ($edit == true) : ?> value="<?php echo $rate; ?>" <?php endif ?> id="setting-input-2" placeholder="₱ 0.00" autocomplete="off" required value="<?php echo $_POST['rate']; ?>">
                                    </div>
                                    <?php if ($edit == true) : ?>
                                        <button type="submit" name="update" class="btn app-btn-primary">Update</button>
                                        <a href="manage-worker-positions.php" class="btn app-btn-primary" style="float:right;background-color:grey">Cancel</a>
                                    <?php else : ?>
                                        <button type="submit" name="submit" class="btn app-btn-primary">Add</button>
                                    <?php endif ?>
                                </form>
                            </div>
                            <!--//app-card-body-->
                        </div>
                        <!--//app-card-->
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form">
                                    <div class="mb-3">
                                        <table id="myTable" class="table app-table-hover mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">Position</th>
                                                    <th class="cell">Hourly Rate</th>
                                                    <th class="cell">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM positions ORDER by position ASC";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if worker record are existing in db
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $id = $row['id'];
                                                        $position = $row['position'];
                                                        $rate = $row['rate'];
                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 0.5em"><?php echo $position; ?></td>
                                                            <td class="cell" style="padding-top: 0.5em">₱ <?php echo number_format($row['rate'], 2, '.', ','); ?></td>
                                                            <td>
                                                                <a href="manage-worker-positions.php?ID=<?php echo $id; ?>" class="btn app-btn btn-info" style="color:white"><i class="fa fa-edit"></i> Edit</a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<script>alert('No Positions Found!')</script>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
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

    <!-- Datatables -->
    <script src="dataTables/jquery-3.5.1.js"></script>
    <script src="dataTables/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "aaSorting": [],
                "sScrollX": "100%",
                "sScrollXInner": "100%",
                "bScrollCollapse": true
            });
        });
    </script>
</body>

<script>
    setTimeout(function() {
        document.getElementById("alert").style.display = "none";
    }, 3000);
</script>

</html>