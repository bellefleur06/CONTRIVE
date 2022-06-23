<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTRIVE | Print Project List</title>

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

    <div class="container table-responsive">
        <br>
        <center>
            <img src="../assets/images/kcs.png" style="width:20em">
        </center>
        <br>
        <table id="ready" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align:center" colspan="9">Projects Master List</th>
                </tr>
                <tr>

                    <th>Type</th>
                    <th>Name</th>
                    <th>Engineer</th>
                    <th>Location</th>
                    <th>Client Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM staffs, projects WHERE staffs.id = projects.engineer_id";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                //check if projects exist
                if ($count > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['id'];
                        $projecttype = $row['type'];
                        $projectname = $row['name'];
                        $full_name = $row['full_name'];
                        $location = $row['location'];
                        $clientname = $row['client_name'];
                        $start_date = $row['start_date'];
                        $status = $row['status'];
                        $date_start = date("M d, Y", strtotime($start_date));
                        $end_date = $row['end_date'];
                        $date_end = date("M d, Y", strtotime($end_date));
                        $date_added = $row['date_added'];
                        $added_date = date("M d, Y", strtotime($date_added));
                ?>
                        <tr>
                            <td><?php echo $projecttype; ?></td>
                            <td><?php echo $projectname; ?></td>
                            <td><?php echo $full_name; ?></td>
                            <td><?php echo $location; ?></td>
                            <td><?php echo $clientname; ?></td>
                            <td><?php echo $date_start; ?></td>
                            <td><?php echo $date_end; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $added_date; ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<script>alert('No Projects Found!')</script>";
                }
                ?>
            </tbody>
        </table>
        <button class="btn btn-info noprint mb-3" style="color:white; float:right" onclick="print()"><i class="fa fa-print"></i> Click here to Print</button>
    </div>
</body>

</html>