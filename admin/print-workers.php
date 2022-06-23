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
    <title>CONTRIVE | Print Workers List</title>

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
                    <th style="text-align:center" colspan="13">Workers Master List</th>
                </tr>
                <tr>
                    <th class="cell">ID Number</th>
                    <th class="cell">Full Name</th>
                    <th class="cell">Birthday</th>
                    <th class="cell">Age</th>
                    <th class="cell">Gender</th>
                    <th class="cell">Address</th>
                    <th class="cell">Contact No.</th>
                    <th class="cell">Civil Status</th>
                    <th class="cell">Position</th>
                    <th class="cell">Hours Per Shift (Hrs/Day)</th>
                    <th class="cell">Rate</th>
                    <th class="cell">Status</th>
                    <th class="cell">Date Employed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM workers";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                //check if projects exist
                if ($count > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {
                        $emp_id = $row['emp_id'];
                        $last_name = $row['last_name'];
                        $first_name = $row['first_name'];
                        $middle_name = $row['middle_name'];
                        $birthday = $row['birthday'];
                        $birth_date = date("M d, Y", strtotime($birthday));
                        $age = $row['age'];
                        $gender = $row['gender'];
                        $address = $row['address'];
                        $contact = $row['contact'];
                        $civil_status = $row['civil_status'];
                        $position = $row['position'];
                        $hours_per_day = $row['hours_per_day'];
                        $rate = $row['rate'];
                        $status = $row['status'];
                        $date_added = $row['date_added'];
                        $hired_date = date("M d, Y", strtotime($date_added));
                ?>
                        <tr>
                            <td style="width:10%"><?php echo $emp_id; ?></td>
                            <td style="width:10%"><?php echo $first_name . " " . $middle_name . " " . $last_name; ?></td>
                            <td><?php echo $birth_date; ?></td>
                            <td><?php echo $age; ?></td>
                            <td><?php echo $gender; ?></td>
                            <td><?php echo $address; ?></td>
                            <td><?php echo $contact; ?></td>
                            <td><?php echo $civil_status; ?></td>
                            <td><?php echo $position; ?></td>
                            <td><?php echo $hours_per_day; ?> hrs</td>
                            <td style="width:10%">₱ <?php echo $rate; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $hired_date; ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<script>alert('No Workers Found!')</script>";
                }
                ?>
            </tbody>
        </table>
        <button class="btn btn-info noprint mb-3" style="color:white; float:right" onclick="print()"><i class="fa fa-print"></i> Click here to Print</button>


        <!-- Javascript -->
        <script src="assets/plugins/popper.min.js"></script>
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

        <!-- Page Specific JS -->
        <script src="assets/js/app.js"></script>

        <!-- Datatables -->
        <script src="dataTables/jquery-3.5.1.js"></script>
        <script src="dataTables/jquery.dataTables.min.js"></script>

</body>

</html>