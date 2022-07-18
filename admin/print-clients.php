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
    <title>CONTRIVE | Print Client List</title>

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
                    <th style="text-align:center" colspan="5">Clients Master List</th>
                </tr>
                <tr>
                    <th>Full Name</th>
                    <th>Contact No.</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM clients";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                //check if clients exist
                if ($count > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['id'];
                        $clientname = $row['name'];
                        $contact = $row['contact'];
                        $email = $row['email'];
                        $address = $row['address'];
                        $date_added = $row['date_added'];
                        $status = $row['status'];
                        $date = date("M d, Y", strtotime($date_added));
                ?>
                        <tr>
                            <td><?php echo $clientname; ?></td>
                            <td><?php echo $contact; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $address; ?></td>
                            <?php if ($status == '1') :?>
                            <td><p>Active</p></td>
                            <?php else : ?>
                            <td><p>Inactive</p></td>
                            <?php endif; ?>
                            <td><?php echo $date; ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<script>alert('No Clients Found!')</script>";
                }
                ?>
            </tbody>
        </table>
        <button class="btn btn-info noprint mb-3" style="color:white; float:right" onclick="print()"><i class="fa fa-print"></i> Click here to Print</button>

</body>

</html>