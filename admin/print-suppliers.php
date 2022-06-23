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
    <title>CONTRIVE | Print Suppliers List</title>

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

    <div class="container table-reponsive">
        <br>
        <center>
            <img src="../assets/images/kcs.png" style="width:20em">
        </center>
        <br>
        <table id="ready" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align:center" colspan="8">Suppliers Master List</th>
                </tr>
                <tr>
                    <th class="cell">Category</th>
                    <th class="cell">Name</th>
                    <th class="cell">Contact Person</th>
                    <th class="cell">Address</th>
                    <th class="cell">Contact No.</th>
                    <th class="cell">Email</th>
                    <th class="cell">Status</th>
                    <th class="cell">Date Added</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM suppliers";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                //check if projects exist
                if ($count > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['category_id'];
                        $name = $row['name'];
                        $person = $row['person'];
                        $contact = $row['contact'];
                        $address = $row['address'];
                        $email = $row['email'];
                        $date_added = $row['date_added'];
                        $hired_date = date("M d, Y", strtotime($date_added));
                        $status = $row['status'];
                ?>
                        <tr>
                            <?php
                            if ($row['category_id'] == 1) {
                            ?>
                                <td>Foundation</td>
                            <?php
                            } else if ($row['category_id'] == 2) {
                            ?>
                                <td>Electrical</td>
                            <?php
                            } else if ($row['category_id'] == 3) {
                            ?>
                                <td>Plumbing</td>
                            <?php
                            }
                            ?>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $person; ?></td>
                            <td><?php echo $address; ?></td>
                            <td><?php echo $contact; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $status; ?></td>
                            <td style="width:10%"><?php echo $hired_date; ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<script>alert('No Suppliers Found!')</script>";
                }
                ?>
            </tbody>
        </table>
        <button class="btn btn-info noprint mb-3" style="color:white; float:right" onclick="print()"><i class="fa fa-print"></i> Click here to Print</button>
</body>

</html>