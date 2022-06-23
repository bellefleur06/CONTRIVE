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

//check if there are worker records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if worker exist
    if ($count > 0) {

        $receivables_id = $row['id'];
    } else {

        $_SESSION['worker-not-found'] = "Worker Data Not Found.";
        header("Location: worker-reports.php");
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['worker-not-found'] = "Worker Data Not Found.";
    header("Location: worker-reports.php");
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
    include('accountant-navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-12">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <a href="print-billing-report.php?ID=<?php echo $id; ?>" target="_blank" class="btn app-btn btn-info mb-3" style="color:white"><i class=" fa fa-print"></i> Print Report Details</a>
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table class="table app-table-hover text-left">
                                            <tr>
                                                <td class="cell" colspan="2">
                                                    <h1 class="app-page-title">Billing Report Details</h1>
                                                </td>
                                                <td>
                                                    <a href="billing-report.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Invoice ID:</b> #<?php echo $row['invoice_id']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Client Name:</b> <?php echo $row['client_name']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Invoice Amount: </b> ₱<?php echo number_format($row['total_invoice'], 2, '.', ',') ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3" colspan="2">
                                                    <b>Date Created:</b> <?php echo $date = date("M d, Y - h:i a", strtotime($row['receivable_date_added'])); ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Invoice Status:</b> <?php echo $row['receivable_status']; ?>
                                                </td>
                                            </tr>
                                            <?php if ($row['receivable_status'] == 'Paid') : ?>
                                                <tr>
                                                    <td class="cell py-3" colspan="2">
                                                        <b>Total Receivings: </b> ₱<?php echo number_format($row['total_invoice'] - $row['total_remaining'], 2, '.', ',') ?>
                                                    </td>
                                                    <td class="cell py-3">
                                                        <b>Date Received:</b> <?php echo $date = date("M d, Y - h:i a", strtotime($row['receivable_date_received'])); ?>
                                                    </td>
                                                </tr>
                                            <?php elseif ($row['status'] == 'Partial') : ?>
                                                <tr>
                                                    <td class="cell py-3" colspan="3">
                                                        <b>Total Partial Payment: </b> ₱<?php echo number_format($row['total_invoice'] - $row['total_remaining'], 2, '.', ',') ?>
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
                                            <h1 class="app-page-title">Receivables Collection Breakdown</h1>
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
                                                            <td class="cell" style="padding-top: 0.5em">
                                                                #<?php echo $invoice_id; ?>
                                                            </td>
                                                            <td class="cell" style="padding-top: 0.5em">
                                                                ₱<?php echo number_format($price, 2, '.', ',') ?>
                                                            </td>
                                                            <?php if ($payment_status == 'Paid') { ?>
                                                                <td class="cell" style="padding-top: 0.5em">
                                                                    Full Payment
                                                                </td>
                                                            <?php } else if ($payment_status == 'Partial') { ?>
                                                                <td class="cell" style="padding-top: 0.5em">
                                                                    Partial Payment
                                                                </td>
                                                            <?php } ?>
                                                            <td class="cell" style="padding-top: 0.5em">
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