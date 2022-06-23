<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM materials, orders, payables WHERE payables.product_id = materials.id AND orders.order_id = payables.order_id AND payables.id = '$id' ORDER BY payables.date_ordered";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$purchase_id = $row['id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTRIVE | Print Purchase Report</title>

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
                    <th style="text-align:center" colspan="3">Purchase Report Details</th>
                </tr>
                <tr>
                    <td>
                        <b>Order ID:</b> <?php echo $row['order_id']; ?>
                    </td>
                    <td>
                        <b>Order:</b> <?php echo $row['products']; ?>
                    </td>
                    <td>
                        <b>Supplier: </b> <?php echo $row['supplier']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Price: </b> ₱<?php echo number_format($row['product_price'], 2, '.', ',') ?>
                    </td>
                    <td>
                        <b>Quantity:</b> <?php echo $row['qty'] . " " . $row['unit']; ?>
                    </td>
                    <td>
                        <b>Total:</b> ₱<?php echo number_format($row['amount_paid'], 2, '.', ',') ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>Date Purchase:</b> <?php echo $date = date("M d, Y - h:i a", strtotime($row['date_ordered'])); ?>
                    </td>
                    <td>
                        <b>Order Payment Status:</b> <?php echo $row['status']; ?>
                    </td>
                </tr>
                <?php if ($row['status'] == 'Paid') : ?>
                    <tr>
                        <td colspan="2">
                            <b>Total Payment: </b> ₱<?php
                                                    echo number_format($row['amount_paid'] - $row['total_amount'], 2, '.', ',') ?>
                        </td>
                        <td>
                            <b>Date Paid:</b> <?php echo $date = date("M d, Y - h:i a", strtotime($row['date_paid'])); ?>
                        </td>
                    </tr>
                <?php elseif ($row['status'] == 'Partial') : ?>
                    <tr>
                        <td colspan="3">
                            <b>Total Partial Payment: </b> ₱<?php
                                                            echo number_format($row['amount_paid'] - $row['total_amount'], 2, '.', ',') ?>
                        </td>
                    </tr>
                <?php endif; ?>
        </table>
        <br>

        <table id="ready" class="table table-bordered text-center" style="width:100%">
            <thead>
                <tr>
                    <th colspan="4" style="text-align:center">Payables Payment Breakdown</th>
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

                $sql = "SELECT * FROM orders, payables, history WHERE orders.order_id = payables.order_id AND history.payables_id = payables.id AND payables.id = '$purchase_id'";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                if ($count > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                        $order_id = $row['order_id'];
                        $price = $row['price'];
                        $payable_status = $row['payable_status'];
                        $date_paid = $row['date_paid'];
                ?>
                        <tr>
                            <td>
                                <?php echo $order_id; ?>
                            </td>
                            <td>
                                ₱<?php echo number_format($price, 2, '.', ',') ?>
                            </td>
                            <?php if ($payable_status == 'Paid') { ?>
                                <td>
                                    Full Payment
                                </td>
                            <?php } else if ($payable_status == 'Partial') { ?>
                                <td>
                                    Partial Payment
                                </td>
                            <?php } ?>
                            <td>
                                <?php echo $date = date("M d, Y - h:i a", strtotime($date_paid)); ?>
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