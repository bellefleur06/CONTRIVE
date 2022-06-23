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

//check if there are worker records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if worker exist
    if ($count == 1) {

        $purchase_id = $row['id'];
    } else {

        $_SESSION['purchase-not-found'] = "Purchase Data Not Found.";
        header("Location: purchase-report.php");
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['purchase-not-found'] = "Purchase Data Not Found.";
    header("Location: purchase-report.php");
}
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Report Details</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link id="theme-style" rel="stylesheet" href="dataTables/jquery.dataTables.min.css">
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="../assets/css/portal.css">
    <link rel="stylesheet" href="../assets/css/style.css" />

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="app">

    <?php $page = 'report';
    include('engineer-navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-12">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <a href="print-purchase-report.php?ID=<?php echo $id; ?>" target="_blank" class="btn app-btn btn-info mb-3" style="color:white"><i class=" fa fa-print"></i> Print Report Details</a>
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table class="table app-table-hover text-left">
                                            <tr>
                                                <td class="cell" colspan="2">
                                                    <h1 class="app-page-title">Purchase Report Details</h1>
                                                </td>
                                                <td>
                                                    <a href="purchase-report.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Order ID:</b> <?php echo $row['order_id']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Order:</b> <?php echo $row['products']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Supplier: </b> <?php echo $row['supplier']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Price: </b> ₱<?php echo number_format($row['product_price'], 2, '.', ',') ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Quantity:</b> <?php echo $row['qty'] . " " . $row['unit']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Total:</b> ₱<?php echo number_format($row['amount_paid'], 2, '.', ',') ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3" colspan="2">
                                                    <b>Date Purchase:</b> <?php echo $date = date("M d, Y - h:i a", strtotime($row['date_ordered'])); ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Order Payment Status:</b> <?php echo $row['status']; ?>
                                                </td>
                                            </tr>
                                            <?php if ($row['status'] == 'Paid') : ?>
                                                <tr>
                                                    <td class="cell py-3" colspan="2">
                                                        <b>Total Payment: </b> ₱<?php
                                                                                echo number_format($row['amount_paid'] - $row['total_amount'], 2, '.', ',') ?>
                                                    </td>
                                                    <td class="cell py-3">
                                                        <b>Date Paid:</b> <?php echo $date = date("M d, Y - h:i a", strtotime($row['date_paid'])); ?>
                                                    </td>
                                                </tr>
                                            <?php elseif ($row['status'] == 'Partial') : ?>
                                                <tr>
                                                    <td class="cell py-3" colspan="3">
                                                        <b>Total Partial Payment: </b> ₱<?php
                                                                                        echo number_format($row['amount_paid'] - $row['total_amount'], 2, '.', ',') ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-12">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table id="myTable" class="table app-table-hover text-center">
                                            <h1 class="app-page-title">Payables Payment Breakdown</h1>
                                            <hr>
                                            <thead>
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
                                                            <td class="cell" style="padding-top: 0.5em">
                                                                <?php echo $order_id; ?>
                                                            </td>
                                                            <td class="cell" style="padding-top: 0.5em">
                                                                ₱<?php echo number_format($price, 2, '.', ',') ?>
                                                            </td>
                                                            <?php if ($payable_status == 'Paid') { ?>
                                                                <td class="cell" style="padding-top: 0.5em">
                                                                    Full Payment
                                                                </td>
                                                            <?php } else if ($payable_status == 'Partial') { ?>
                                                                <td class="cell" style="padding-top: 0.5em">
                                                                    Partial Payment
                                                                </td>
                                                            <?php } ?>
                                                            <td class="cell" style="padding-top: 0.5em">
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
                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--//container-fluid-->

            </div>
            <!--//app-content-->

        </div>
        <!--//app-wrapper-->

        <!-- Javascript -->
        <script src="assets/plugins/popper.min.js"></script>
        <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

        <!-- Page Specific JS -->
        <script src="assets/js/app.js"></script>

        <!-- Datatables -->
        <script src="dataTables/jquery-3.5.1.js"></script>
        <script src="dataTables/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    "aaSorting": [],
                    "sScrollX": "100%",
                    "sScrollXInner": "100%",
                    "bScrollCollapse": true
                });
            });
        </script>
</body>

</html>