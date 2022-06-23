<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT *, COUNT(*) as count FROM projects, clients WHERE projects.client_name = clients.name AND clients.id = '$id' GROUP BY clients.name";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$client_name = $row['name'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTRIVE | Print Client Report</title>

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
                    <th style="text-align:center" colspan="3"> Client Report Details
                    </th>
                </tr>
                <tr>
                    <td>
                        <b>Name:</b> <?php echo $row['name']; ?>
                    </td>
                    <td colspan="2">
                        <b>Address:</b> <?php echo $row['address']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Contact No.:</b> <?php echo $row['contact']; ?>
                    </td>
                    <td>
                        <b>Email:</b> <?php echo $row['email']; ?>
                    </td>
                    <td>
                        <b>Date Added:</b> <?php echo $date = date("M d, Y", strtotime($row['date_added'])); ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <b>No. of Requested Projects:</b>
                        <?php echo $row['count']; ?>
                    </td>
                </tr>
        </table>
        <br>
        <?php

        $sql = "SELECT * FROM staffs, projects WHERE projects.engineer_id = staffs.id AND projects.client_name = '$client_name'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);

        //check if projects exist
        if ($count > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

        ?>
                <table id="ready" class="table table-bordered" style="width:100%">
                    <tr>
                        <th colspan="3" style="text-align:center"> Client's Requested Project Details
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <b>Project Name:</b> <?php echo $row['name']; ?>
                        </td>
                        <td>
                            <b>Type:</b> <?php echo $row['type']; ?>
                        </td>
                        <td>
                            <b>Location:</b> <?php echo $row['location']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Engineer:</b> <?php echo $row['full_name']; ?>
                        </td>
                        <td colspan="2">
                            <b>Description:</b> <?php echo $row['project_description']; ?>
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
        <?php
            }
        }
        ?>
        <button class="btn btn-info noprint mb-3" style="color:white; float:right" onclick="print()"><i class="fa fa-print"></i> Click here to Print</button>

</body>

</html>