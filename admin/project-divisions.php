<?php

include('../connections/config.php');

$page = 'clients';

if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = 0;
$edit = false;
$update = false;

$id = $_GET['ID'];

$sql = "SELECT * FROM projects WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are project records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if project exist
    if ($count == 1) {

        $project_id = $row['id'];
        $project_name = $row['name'];
    } else {

        $_SESSION['project-not-found'] = "Project Not Found.";
        header("Location: manage-projects.php");
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['project-not-found'] = "Project Not Found.";
    header("Location: manage-projects.php");
}

//add division
if (isset($_POST['submit'])) {

    $project_id = mysqli_real_escape_string($conn, $_POST['project_id']);
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $division_name = mysqli_real_escape_string($conn, $_POST['division_name']);
    $progress = 0;
    $activity = "Add New Project Division For " . $project_name . " - " .  $division_name;

    $sql = "INSERT INTO progress (project_id, project_name, division_name, progress) VALUES ('$project_id', '$project_name', '$division_name', '$progress')";
    $result = mysqli_query($conn, $sql);

    //check if insert process if true
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

        $_SESSION['add-division'] = "Project Division Added Successfully!";

        //clear texboxes if the result is true
        $_POST['division_name'] = "";
    } else {

        $_SESSION['failed-to-add'] = "Failed to Add Division.";
    }
}

//edit button

if (isset($_GET['ID']) && isset($_GET['id'])) {

    $project_id = $_GET['ID'];
    $division_id = $_GET['id'];
    $edit = true;

    $sql = "SELECT * FROM progress WHERE id = '$division_id' AND project_id = $project_id;";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($result == TRUE) {

        $count = mysqli_num_rows($result);

        if ($count == 1) {
            $division_name = $row['division_name'];
            $progress = $row['progress'];
        }
    }
}

//edit division/progress
if (isset($_POST['update'])) {

    $project_name =  mysqli_real_escape_string($conn, $_POST['project_name']);
    $division_name =  mysqli_real_escape_string($conn, $_POST['division_name']);
    $progress =  mysqli_real_escape_string($conn, $_POST['progress']);

    $activity = "Update Project Division Details For " . $project_name . " - " . $division_name . " - " . $progress . "%";

    $sql = "UPDATE progress SET division_name = '$division_name' WHERE id = $division_id";
    $result =  mysqli_query($conn, $sql);

    //check if update process if true
    if ($result == TRUE) {

        $sql = "UPDATE staffs SET last_activity = now() WHERE id = '{$_SESSION['id']}'";
        $result = mysqli_query($conn, $sql);

        // $edit = false;

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

        $_SESSION['update-division-progress'] = "Division Details Updated Successfully!";
    } else {

        $_SESSION['failed-to-update'] = "Failed to Update Division Details.";
    }
}

//update button

if (isset($_GET['ID']) && isset($_GET['update'])) {

    $project_id = $_GET['ID'];
    $division_id = $_GET['update'];
    $update = true;

    $sql = "SELECT * FROM progress WHERE id = '$division_id' AND project_id = $project_id;";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($result == TRUE) {

        $count = mysqli_num_rows($result);

        if ($count == 1) {
            $divisions_name = $row['division_name'];
            $division_progress = $row['progress'];
        }
    }
}

//add updates
if (isset($_POST['add'])) {

    $project_id = mysqli_real_escape_string($conn, $_POST['projectID']);
    $project_name = mysqli_real_escape_string($conn, $_POST['projectName']);
    $division_id = mysqli_real_escape_string($conn, $_POST['divisionID']);
    $division_name = mysqli_real_escape_string($conn, $_POST['updateDivision']);
    $details = mysqli_real_escape_string($conn, $_POST['updateDetails']);
    $progress = mysqli_real_escape_string($conn, $_POST['updateProgress']);
    $activity = "Add New Project Progress Update For " . $project_name . " - " .  $division_name . " - " .  $progress . "%";

    $sql = "INSERT INTO updates (project_id, project_name, division_id, division_name, progress, details, user_id) VALUES ('$project_id', '$project_name', '$division_id', '$division_name', '$progress', '$details', '{$_SESSION['id']}')";
    $result = mysqli_query($conn, $sql);

    //check if insert process if true
    if ($result == TRUE) {

        $sql = "UPDATE progress SET progress = '$progress' WHERE id = '$division_id'";
        $result = mysqli_query($conn, $sql);

        //update last activity date and time
        if ($result = TRUE) {
        } else {
            echo "<script>alert('Error in Updating Project Division Progress')</script>";
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

        $_SESSION['add-update'] = "Project Update Added Successfully!";
    } else {

        $_SESSION['failed-to-add'] = "Failed to Add Project Update.";
    }
}


?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Project Divisions</title>

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

    <?php $page = 'project';
    include('navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="manage-projects.php">Manage Projects</a></li>
                    <li class="breadcrumb-item active"><a href="project-details.php?ID=<?php echo $project_id; ?>">Project Details</a></li>
                    <li class="breadcrumb-item active">Project Divisions</li>
                </ol>
                <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-chart-bar"></i></span> "<?php echo $project_name; ?>" Project Divisions </h1>
                <a href="project-details.php?ID=<?php echo $project_id; ?>" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-arrow-left"></i> Go Back</a>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['add-division'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['add-division']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['add-division']);
                }
                if (isset($_SESSION['add-update'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['add-update']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['add-update']);
                }
                if (isset($_SESSION['failed-to-add'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-add']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['failed-to-add']);
                }
                if (isset($_SESSION['update-division-progress'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['update-division-progress']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['update-division-progress']);
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
                        <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-list"></i></span> Add Project Divisions</h1>
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Division Name: </label>
                                        <input id="name" type="text" name="division_name" class="form-control" <?php if ($edit == true) : ?> value="<?php echo $division_name; ?>" <?php endif ?> autocomplete="off" required>
                                    </div>
                                    <input id="project_id" type="hidden" name="project_id" class="form-control" required readonly value="<?php echo $project_id; ?>">
                                    <input id="project_name" type="hidden" name="project_name" class="form-control" required readonly value="<?php echo $project_name; ?>">
                                    <?php if ($edit == true) : ?>
                                        <button type="submit" name="update" class="btn app-btn-primary">Update</button>
                                    <?php else : ?>
                                        <button type="submit" name="submit" class="btn app-btn-primary">Add</button>
                                    <?php endif ?>
                                    <a href="project-divisions.php?ID=<?php echo $project_id; ?>" class="btn app-btn-primary" style="float:right;background-color:grey">Cancel</a>
                                </form>
                            </div>
                            <!--//app-card-body-->
                        </div>
                    </div>

                    <div class="col-12 col-md-8">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form-table">
                                    <div class="mb-3">
                                        <table id="myTable" class="table app-table-hover mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">Division</th>
                                                    <th class="cell">Progress (%)</th>
                                                    <th class="cell">Date Added</th>
                                                    <th class="cell">Actions</th>
                                                    <th class="cell"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $id = $_GET['ID'];

                                                $sql = "SELECT * FROM progress WHERE project_id = $id";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if clients exist
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $id = $row['id'];
                                                        $division_name = $row['division_name'];
                                                        $progress = $row['progress'];
                                                        $date_updated = $row['date_updated'];
                                                        $date = date("M d, Y", strtotime($date_updated));
                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 1em">
                                                                <?php echo $division_name; ?>
                                                            </td>
                                                            <?php if ($progress == 100) { ?>
                                                                <td class="cell" style="padding-top: 1em;font-weight:bold; color:green">
                                                                    <?php echo $progress . "%"; ?>
                                                                </td>
                                                            <?php } else if ($progress == 0) { ?>
                                                                <td class="cell" style="padding-top: 1em; font-weight:bold; color:black">
                                                                    <?php echo $progress . "%"; ?>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td class="cell" style="padding-top: 1em; font-weight:bold; color:blue">
                                                                    <?php echo $progress . "%"; ?>
                                                                </td>
                                                            <?php } ?>
                                                            <td class="cell">
                                                                <?php echo $date; ?>
                                                            </td>
                                                            <td>
                                                                <a href="project-divisions.php?ID=<?php echo $project_id; ?>&id=<?php echo $id; ?>" class="btn app-btn btn-info" style="color:white"><i class="fa fa-edit"></i> Edit</a>
                                                            </td>
                                                            <td>
                                                                <a href="project-divisions.php?ID=<?php echo $project_id; ?>&update=<?php echo $id; ?>" class="btn app-btn btn-primary" style="color:white"><i class="fa fa-plus"></i> Add Project Update</a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<script>alert('No Project Divisions Found!')</script>";
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

                    <?php if ($update == true) : ?>

                        <hr class="mb-1">

                        <div class="col-12 col-md-12">
                            <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-list"></i></span> Add Project Updates</h1>
                            <div class="app-card app-card-settings shadow-sm p-4">
                                <div class="app-card-body">
                                    <form method="post">
                                        <div class="mb-3">
                                            <label for="setting-input-3" class="form-label">Division Name: </label>
                                            <input id="name" type="text" name="updateDivision" class="form-control" <?php if ($update == true) : ?> value="<?php echo $divisions_name; ?>" <?php endif ?> autocomplete="off" readonly required>
                                        </div>
                                        <input id="project_id" type="hidden" name="divisionID" class="form-control" required readonly value="<?php echo $division_id; ?>">
                                        <input id="project_id" type="hidden" name="projectID" class="form-control" required readonly value="<?php echo $project_id; ?>">
                                        <input id="project_name" type="hidden" name="projectName" class="form-control" required readonly value="<?php echo $project_name; ?>">
                                        <div class="mb-3">
                                            <label for="setting-input-2" class="form-label">Details: </label>
                                            <textarea style="height: 10em" name="updateDetails" id="details" class="form-control" rows="3" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="setting-input-3" class="form-label">Progress (%): </label>
                                            <input id="name" type="number" name="updateProgress" class="form-control" <?php if ($update == true) : ?> value="<?php echo $division_progress; ?>" <?php endif ?> autocomplete="off" required min="0" max="100">
                                        </div>
                                        <button type="submit" name="add" class="btn app-btn-primary">Add</button>
                                        <a href="project-divisions.php?ID=<?php echo $project_id; ?>" class="btn app-btn-primary" style="float:right;background-color:grey">Cancel</a>
                                    </form>
                                </div>
                                <!--//app-card-body-->
                            </div>
                        </div>

                    <?php endif ?>

                </div>
                <!--//row-->
                <hr class="mb-4">
            </div>
            <!--//container-fluid-->
        </div>
        <!--//app-content-->
    </div>
    <!--//app-wrapper-->


    <!-- <script>
        function myFunction() {
            var x = document.getElementById("myDiv");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script> -->

    <!-- fetch material details script -->
    <script>
        function fetch_select(val) {
            $.ajax({
                url: "fetch-material.php",
                type: "POST",
                data: {
                    "get_option": val
                },
                dataType: "JSON",
                success: function(data) {
                    $('#name').val((data[0].name));
                    $('#category_id').val((data[0].category_id));
                    $('#category_name').val((data[0].category_name));
                    $('#description').val((data[0].description));
                    $('#unit').val((data[0].unit));
                    $('#stocks').val((data[0].stocks));
                    $('#price').val((data[0].price));
                }

            });
        }
    </script>

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