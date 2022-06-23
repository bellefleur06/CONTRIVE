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

$project_id = $row['id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTRIVE | Print Project Report</title>

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <link id="theme-style" rel="stylesheet" href="assets/css/style.css">

    <style type="text/css" media="print">
        @media print {

            .noprint,
            .noprint * {
                display: none !important;

            }
        }
    </style>

</head>

<body>

    <div class="container">
        <br>
        <center>
            <img src="../assets/images/kcs.png" style="width:20em">
        </center>
        <br>
        <table id="ready" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align:center" colspan="3"> Project Report Details
                    </th>
                </tr>
                <tr>
                    <td>
                        <b>Name:</b> <?php echo $row['name']; ?>
                    </td>
                    <td>
                        <b>Type:</b> <?php echo $row['type']; ?>
                    </td>
                    <td>
                        <b>Description:</b> <?php echo $row['project_description']; ?>
                    </td>

                </tr>
                <tr>
                    <td>
                        <b>Location:</b> <?php echo $row['location']; ?>
                    </td>
                    <td>
                        <b>Client Name:</b> <?php echo $row['client_name']; ?>
                    </td>
                    <td>
                        <b>Engineer:</b> <?php echo $row['full_name']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Start Date:</b> <?php echo $date = date("M d, Y", strtotime($row['start_date'])); ?>
                    </td>
                    <td>
                        <b>End Date:</b> <?php echo $date = date("M d, Y", strtotime($row['end_date'])); ?>
                    </td>
                    <td>
                        <b>Status:</b> <?php echo $row['status']; ?>
                    </td>
                </tr>
        </table>
        <br>

        <table id="ready" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>
                        Project Divisions
                    </th>
                    <th>
                        Progress (%)
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
                        <td>
                            <?php echo $division_name; ?>
                        </td>
                        <?php if ($progress == "") { ?>
                            <td>
                                <div id="outter" style="width:100%; 
															background-color:#ddd;height:30px; text-align:center;padding-left:5px;padding-left:5px; line-height:30px; color:black;">
                                    <?php echo $progress . "0%"; ?>
                                </div>
                            </td>
                        <?php } else if ($progress == 0) { ?>
                            <td>
                                <div id="outter" style="width:100%; background-color:#ddd; height:30px; text-align:left; padding-left:5px; line-height:30px; color:black;">
                                    <?php echo $percent . "%"; ?>
                                </div>
                            </td>
                        <?php } else if ($progress == 100) { ?>
                            <td>
                                <div id="outter" style="width:100%; background-color:#ddd">
                                    <div id="inner" class="progress-bar progress-bar-striped bg-success" style="width:<?php echo $percent; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
                                        <?php echo $percent . "%"; ?>
                                    </div>
                                </div>
                            </td>
                        <?php } else { ?>
                            <td>
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

                <th>Total Project Progress</th>
                <?php if ($total_progress == "") { ?>
                    <td>
                        <div id="outter" style="width:100%; 
													background-color:#ddd;height:30px; text-align:center; line-height:30px; color:black;">
                            <?php echo $total_progress . "0%"; ?>
                        </div>
                    </td>
                <?php } else if ($total_progress == 0) { ?>
                    <td>
                        <div id="outter" style="width:100%; 
													background-color:#ddd;height:30px; text-align:center; line-height:30px; color:black;">
                            <?php echo $total_progress . "%"; ?>
                        </div>
                    </td>
                <?php } else if ($total_progress == 100) { ?>
                    <td>
                        <div id="outter" style="width:100%; 
													background-color:#ddd">
                            <div id="inner" class="progress-bar progress-bar-striped bg-success" style="width:<?php echo $total_progress; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
                                <?php echo $total_progress . "%"; ?>
                            </div>
                        </div>
                    </td>
                <?php } else { ?>
                    <td>
                        <div id="outter" style="width:100%; background-color:#ddd">
                            <div id="inner" class="progress-bar progress-bar-striped bg-info" style="width:<?php echo $total_progress; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
                                <?php echo $total_progress . "%"; ?>
                            </div>
                        </div>
                    </td>
                <?php } ?>
            </tr>
        </table>
        <br>

        <table id="ready" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th colspan="5" style="text-align:center">Project Updates</th>
                </tr>
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
                            <td>
                                <?php echo $division_name; ?>
                            </td>
                            <td style="width:13%">
                                <?php echo $progress; ?>%
                            </td>
                            <td>
                                <?php echo $details; ?>
                            </td>
                            <td>
                                <?php echo $full_name; ?>
                            </td>
                            <td style="width:15%">
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
        <br>

        <table id="ready" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th colspan="2" style="text-align:center">Project Materials</th>
                </tr>
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
                            <td>
                                <?php echo $requirement_name; ?>
                            </td>
                            <td style="width:13%">
                                <?php echo $quantity . " " . $unit; ?>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <th class=" cell pt-4" colspan="2">
                            <h1 class="app-page-title" style="text-align:center; color:#d26d69; font-size:2rem;">No Materials Found!</h1>
                        </th>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <table id="ready" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th colspan="2" style="text-align:center">
                        Project Workers
                    </th>
                </tr>
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
                            <td><?php echo $position_name; ?></td>
                            <td><?php echo $first_name . " " . $last_name; ?></td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <th class="cell pt-4" colspan="2">
                            <h1 class="app-page-title" style="text-align:center; color:#d26d69; font-size:2rem;">No Workers Found!</h1>
                        </th>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <button class="btn btn-info noprint mb-3" style="color:white; float:right" onclick="print()"><i class="fa fa-print"></i> Click here to Print</button>

</body>

</html>