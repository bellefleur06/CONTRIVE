<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM staffs WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$staff_id = $row['id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTRIVE | Print Staff Report</title>

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
                    <th style="text-align:center" colspan="3"> Staff Report Details
                    </th>
                </tr>
                <tr>
                    <td class="cell py-3">
                        <b>Name:</b> <?php echo $row['full_name']; ?>
                    </td>
                    <td class="cell py-3">
                        <b>Address:</b> <?php echo $row['address']; ?>
                    </td>
                    <td class="cell py-3">
                        <b>Birthday:</b> <?php echo $date = date("M d, Y", strtotime($row['birthday'])); ?>
                    </td>
                </tr>
                <tr>
                    <td class="cell py-3">
                        <b>Age:</b> <?php echo $row['age']; ?> yrs old
                    </td>
                    <td class="cell py-3">
                        <b>Contact No.:</b> <?php echo $row['contact']; ?>
                    </td>
                    <td class="cell py-3">
                        <b>Civil Status:</b> <?php echo $row['civil_status']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="cell py-3">
                        <b>Access:</b> <?php echo $row['access']; ?>
                    </td>
                    <td class="cell py-3">
                        <b>Date Registered:</b> <?php echo $date = date("M d, Y", strtotime($row['date_added'])); ?>
                    </td>
                    <td class="cell py-3" colspan="2">
                        <b>Status:</b> <?php echo $row['status']; ?>
                    </td>
                </tr>
        </table>
        <br>

        <?php if ($row['access'] == "Engineer") : ?>
            <table id="ready" class="table table-bordered text-center" style="width:100%">
                <thead>
                    <tr>
                        <th style="text-align:center" colspan="2"> Engineer's Projects Participation
                        </th>
                    </tr>
                    <tr>
                        <th>Project</th>
                        <th>Working Dates</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $sql = "SELECT * FROM staffs, projects WHERE staffs.id = projects.engineer_id AND staffs.id = '$staff_id'";
                    $result = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($result);

                    if ($count > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {

                            $project_name = $row['name'];
                            $start_date = $row['start_date'];
                            $end_date = $row['end_date'];
                    ?>
                            <tr>
                                <td>
                                    <?php echo $project_name; ?>
                                </td>
                                <td>
                                    <?php echo $date = date("M d, Y", strtotime($row['start_date'])) . " to " . $date = date("M d, Y", strtotime($row['end_date'])); ?>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <th class="cell pt-4" colspan="5">
                                <h1 class="app-page-title" style="text-align:center; color:#d26d69; font-size:2rem;">No Projects Found!</h1>
                            </th>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php endif; ?>
        <button class="btn btn-info noprint mb-3" style="color:white; float:right" onclick="print()"><i class="fa fa-print"></i> Click here to Print</button>

</body>

</html>