<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM payments, receivables WHERE total_invoice != '' AND receivables.id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$receivables_id = $row['id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTRIVE | Print Billing Report</title>

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
                    <th style="text-align:center" colspan="3">Billing Report Details</th>
                </tr>
                <tr>
                    <td>
                        <b>Invoice ID:</b> #<?php echo $row['invoice_id']; ?>
                    </td>
                    <td>
                        <b>Client Name:</b> <?php echo $row['client_name']; ?>
                    </td>
                    <td>
                        <b>Invoice Amount: </b> ₱<?php echo number_format($row['total_invoice'], 2, '.', ',') ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>Date Created:</b> <?php echo $date = date("M d, Y - h:i a", strtotime($row['receivable_date_added'])); ?>
                    </td>
                    <td>
                        <b>Invoice Status:</b> <?php echo $row['receivable_status']; ?>
                    </td>
                </tr>
                <?php if ($row['receivable_status'] == 'Paid') : ?>
                    <tr>
                        <td colspan="2">
                            <b>Total Receivings: </b> ₱<?php echo number_format($row['total_invoice'] - $row['total_remaining'], 2, '.', ',') ?>
                        </td>
                        <td>
                            <b>Date Received:</b> <?php echo $date = date("M d, Y - h:i a", strtotime($row['receivable_date_received'])); ?>
                        </td>
                    </tr>
                <?php elseif ($row['status'] == 'Partial') : ?>
                    <tr>
                        <td colspan="3">
                            <b>Total Partial Payment: </b> ₱<?php echo number_format($row['total_invoice'] - $row['total_remaining'], 2, '.', ',') ?>
                        </td>
                    </tr>
                <?php endif; ?>
        </table>
        <br>

        <table id="ready" class="table table-bordered text-center" style="width:100%">
            <thead>
                <tr>
                    <th colspan="4" style="text-align:center">Receivables Collection Breakdown</th>
                </tr>
                <tr>
                    <th>Order ID</th>
                    <th>Payment Amount</th>
                    <th>Payment Status</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql = "SELECT * FROM payments, receivables WHERE total_invoice != '' AND payments.receivables_id = receivables.id AND receivables.id = '$receivables_id'";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                if ($count > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                        $invoice_id = $row['invoice_id'];
                        $price = $row['price'];
                        $payment_status = $row['payment_status'];
                        $payment_date = $row['payment_date'];
                ?>
                        <tr>
                            <td>
                                #<?php echo $invoice_id; ?>
                            </td>
                            <td>
                                ₱<?php echo number_format($price, 2, '.', ',') ?>
                            </td>
                            <?php if ($payment_status == 'Paid') { ?>
                                <td>
                                    Full Payment
                                </td>
                            <?php } else if ($payment_status == 'Partial') { ?>
                                <td>
                                    Partial Payment
                                </td>
                            <?php } ?>
                            <td>
                                <?php echo $date = date("M d, Y - h:i a", strtotime($payment_date)); ?>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <th class=" cell pt-4" colspan="5">
                            <h1 class="app-page-title" style="text-align:center; color:#d26d69; font-size:2rem;">No Payments Found!</h1>
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