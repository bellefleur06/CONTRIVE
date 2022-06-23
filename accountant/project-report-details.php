<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM staffs, projects WHERE projects.engineer_id = staffs.id AND projects.id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are worker records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if worker exist
    if ($count == 1) {

        $project_id = $row['id'];
    } else {

        $_SESSION['project-not-found'] = "Project Data Not Found.";
        header("Location: project-reports.php");
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['project-not-found'] = "Project Data Not Found.";
    header("Location: project-reports.php");
}
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Report Details</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link id="theme-style" rel="stylesheet" href="dataTables/jquery.dataTables.min.css">
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="../assets/css/portal.css">
    <link rel="stylesheet" href="../assets/css/style.css" />

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="app">

    <?php $page = 'report';
    include('accountant-navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-12">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <a href="print-project-report.php?ID=<?php echo $id; ?>" target="_blank" class="btn app-btn btn-info mb-3" style="color:white"><i class=" fa fa-print"></i> Print Report Details</a>
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table class="table app-table-hover text-left">
                                            <tr>
                                                <td class="cell" colspan="2">
                                                    <h1 class="app-page-title">Project Report Details</h1>
                                                </td>
                                                <td>
                                                    <a href="project-reports.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Name:</b> <?php echo $row['name']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Type:</b> <?php echo $row['type']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Description:</b> <?php echo $row['project_description']; ?>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Location:</b> <?php echo $row['location']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Client Name:</b> <?php echo $row['client_name']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Engineer:</b> <?php echo $row['full_name']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Start Date:</b> <?php echo $date = date("M d, Y", strtotime($row['start_date'])); ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>End Date:</b> <?php echo $date = date("M d, Y", strtotime($row['end_date'])); ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Status:</b> <?php echo $row['status']; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-12">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <form class="settings-form">
                                            <table class="table app-table-hover text-left">
                                                <thead>
                                                    <tr>
                                                        <th class="cell">
                                                            <h1 class="app-page-title" style="float:left">Project Divisions</h1>
                                                        </th>
                                                        <th class="cell">
                                                            <h1 class="app-page-title" style="float:left">Progress (%)</h1>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <?php

                                                $id = $_GET['ID'];

                                                $sql = "SELECT * FROM progress WHERE project_id = $id";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if clients exist
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $division_name = $row['division_name'];
                                                        $progress = $row['progress'];

                                                        $total =  100;
                                                        $percent = round(($progress / $total) * 100, 1);
                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 1em">
                                                                <label for="setting-input-2" class="form-label"><?php echo $division_name; ?></label>
                                                            </td>
                                                            <?php if ($progress == "") { ?>
                                                                <td class="cell" style="padding-top: 1em">
                                                                    <div id="outter" style="width:100%; 
															background-color:#ddd;height:30px; text-align:center;padding-left:5px;padding-left:5px; line-height:30px; color:black;">
                                                                        <?php echo $progress . "0%"; ?>
                                                                    </div>
                                                                </td>
                                                            <?php } else if ($progress == 0) { ?>
                                                                <td class="cell" style="padding-top: 1em">
                                                                    <div id="outter" style="width:100%; background-color:#ddd; height:30px; text-align:left; padding-left:5px; line-height:30px; color:black;">
                                                                        <?php echo $percent . "%"; ?>
                                                                    </div>
                                                                </td>
                                                            <?php } else if ($progress == 100) { ?>
                                                                <td class="cell" style="padding-top: 1em">
                                                                    <div id="outter" style="width:100%; background-color:#ddd">
                                                                        <div id="inner" class="progress-bar progress-bar-striped bg-success" style="width:<?php echo $percent; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
                                                                            <?php echo $percent . "%"; ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td class="cell" style="padding-top: 1em">
                                                                    <div id="outter" style="width:100%; background-color:#ddd">
                                                                        <div id="inner" class="progress-bar progress-bar-striped bg-warning" style="width:<?php echo $percent; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
                                                                            <?php echo $percent . "%"; ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td class="text-center" colspan="5" style="font-weight:bold; font-size: 1.2em">No data available in the table</td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                                <?php

                                                $id = $_GET['ID'];

                                                $sql = "SELECT ROUND(AVG(progress), 0) AS total_progress FROM progress WHERE project_id = '$id'";
                                                $result = mysqli_query($conn, $sql);
                                                $row = mysqli_fetch_assoc($result);

                                                $total_progress = $row['total_progress'];

                                                ?>
                                                <tr>

                                                    <td class="cell" style="padding-top: 1em; font-weight: bold; font-size:1.1em">Total Project Progress</td>
                                                    <?php if ($total_progress == "") { ?>
                                                        <td class="cell" style="padding-top: 1em">
                                                            <div id="outter" style="width:100%; 
													background-color:#ddd;height:30px; text-align:center; line-height:30px; color:black;">
                                                                <?php echo $total_progress . "0%"; ?>
                                                            </div>
                                                        </td>
                                                    <?php } else if ($total_progress == 0) { ?>
                                                        <td class="cell" style="padding-top: 1em">
                                                            <div id="outter" style="width:100%; 
													background-color:#ddd;height:30px; text-align:center; line-height:30px; color:black;">
                                                                <?php echo $total_progress . "%"; ?>
                                                            </div>
                                                        </td>
                                                    <?php } else if ($total_progress == 100) { ?>
                                                        <td class="cell" style="padding-top: 1em">
                                                            <div id="outter" style="width:100%; 
													background-color:#ddd">
                                                                <div id="inner" class="progress-bar progress-bar-striped bg-success" style="width:<?php echo $total_progress; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
                                                                    <?php echo $total_progress . "%"; ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td class="cell" style="padding-top: 1em">
                                                            <div id="outter" style="width:100%; background-color:#ddd">
                                                                <div id="inner" class="progress-bar progress-bar-striped bg-info" style="width:<?php echo $total_progress; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
                                                                    <?php echo $total_progress . "%"; ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            </table>
                                        </form>
                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-12">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table id="myTable" class="table app-table-hover text-left">
                                            <h1 class="app-page-title">Project Updates</h1>
                                            <hr>
                                            <thead>
                                                <tr>
                                                    <th>Division</th>
                                                    <th>Progress (%)</th>
                                                    <th>Update Details</th>
                                                    <th>Posted By</th>
                                                    <th>Date Updated</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $sql = "SELECT * FROM updates, staffs WHERE updates.user_id = staffs.id AND updates.project_id = '$project_id' ORDER by update_id DESC";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $update_id =  $row['update_id'];
                                                        $project_id =  $row['project_id'];
                                                        $division_name =  $row['division_name'];
                                                        $progress =  $row['progress'];
                                                        $details =  $row['details'];
                                                        $full_name =  $row['full_name'];
                                                        $date_posted =  $row['date_posted'];
                                                ?>
                                                        <tr>
                                                            <td class="cell pt-5">
                                                                <?php echo $division_name; ?>
                                                            </td>
                                                            <td class="cell pt-5" style="width:13%">
                                                                <?php echo $progress; ?>%
                                                            </td>
                                                            <td class="cell">
                                                                <?php echo $details; ?>
                                                            </td>
                                                            <td class="cell pt-4">
                                                                <?php echo $full_name; ?>
                                                            </td>
                                                            <td class="cell pt-3" style="width:15%">
                                                                <?php echo $date = date("M d, Y - h:i a", strtotime($date_posted)); ?>
                                                            </td>

                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <th class=" cell pt-4" colspan="5">
                                                            <h1 class="app-page-title" style="text-align:center; color:#d26d69; font-size:2rem;">No Updates Found!</h1>
                                                        </th>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table id="myTable1" class="table app-table-hover text-left">
                                            <h1 class="app-page-title">Project Materials</h1>
                                            <hr>
                                            <thead>
                                                <tr>
                                                    <th>Material</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $sql = "SELECT * FROM requirements WHERE project_id = $project_id";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {

                                                        $requirement_name = $row['name'];
                                                        $quantity = $row['quantity'];
                                                        $unit = $row['unit'];
                                                ?>
                                                        <tr>
                                                            <td class="cell">
                                                                <?php echo $requirement_name; ?>
                                                            </td>
                                                            <td class="cell" style="width:13%">
                                                                <?php echo $quantity . " " . $unit; ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <th class=" cell pt-4" colspan="5">
                                                            <h1 class="app-page-title" style="text-align:center; color:#d26d69; font-size:2rem;">No Materials Found!</h1>
                                                        </th>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table id="myTable2" class="table app-table-hover text-left">
                                            <h1 class="app-page-title">Project Workers</h1>
                                            <hr>
                                            <thead>
                                                <tr>
                                                    <th>Position</th>
                                                    <th>Worker Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $sql = "SELECT * FROM workers, teams WHERE workers.position_id = teams.position_id AND workers.id = teams.member_id AND project_id = $project_id";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {

                                                        $position_name = $row['position'];
                                                        $first_name = $row['first_name'];
                                                        $last_name = $row['last_name'];
                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 0.5em"><?php echo $position_name; ?></td>
                                                            <td class="cell" style="padding-top: 0.5em"><?php echo $first_name . " " . $last_name; ?></td>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <th class=" cell pt-4" colspan="5">
                                                            <h1 class="app-page-title" style="text-align:center; color:#d26d69; font-size:2rem;">No Workers Found!</h1>
                                                        </th>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>
                        </div>
                    </div>

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
        <script>
            $(document).ready(function() {
                $('#myTable1').DataTable({
                    "aaSorting": [],
                    "sScrollX": "100%",
                    "sScrollXInner": "100%",
                    "bScrollCollapse": true
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#myTable2').DataTable({
                    "aaSorting": [],
                    "sScrollX": "100%",
                    "sScrollXInner": "100%",
                    "bScrollCollapse": true
                });
            });
        </script>

</body>

</html>