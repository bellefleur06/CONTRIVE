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
    <title>CONTRIVE | Print Receivables List</title>

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
        <table id="ready" class="table table-bordered text-center" style="width:100%">
            <thead>
                <tr>
                    <th style="text-align:center" colspan="6">Accounts Receivable List</th>
                </tr>
                <tr>
                    <th class="cell">Receivable ID</th>
                    <th class="cell">Client</th>
                    <th class="cell">Total Amount</th>
                    <th class="cell">Date Created</th>
                    <th class="cell">Status</th>
                    <th class="cell">Date Received</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM receivables WHERE total_invoice != ''";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                //check if clients exist
                if ($count > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['id'];
                        $invoice_id = $row['invoice_id'];
                        $client_name = $row['client_name'];
                        $receivable_date_added = $row['receivable_date_added'];
                        $total_invoice = $row['total_invoice'];
                        $receivable_status = $row['receivable_status'];
                        $receivable_date_received = $row['receivable_date_received'];
                ?>
                        <tr>
                            <td>#<?php echo $invoice_id; ?></td>
                            <td><?php echo $client_name; ?></td>
                            <td>₱<?php echo number_format($total_invoice, 2, '.', ','); ?></td>
                            <td><?php echo date("M d, Y - h:i a", strtotime($receivable_date_added)); ?></td>
                            <?php
                            if ($receivable_status == "Paid") {
                            ?>
                                <td><?php echo $receivable_status; ?></td>
                                <td><?php echo date("M d, Y - h:i a", strtotime($receivable_date_received)); ?></td>
                            <?php
                            } else if ($receivable_status == "Unpaid") {
                            ?>
                                <td><?php echo $receivable_status; ?></td>
                                <td> --- </td>
                            <?php
                            }
                            ?>
                        </tr>
                <?php
                    }
                } else {
                    echo "<script>alert('No Receivables Found!')</script>";
                }
                ?>
            </tbody>
        </table>
        <button class="btn btn-info noprint mb-3" style="color:white; float:right" onclick="print()"><i class="fa fa-print"></i> Click here to Print</button>

</body>

</html>