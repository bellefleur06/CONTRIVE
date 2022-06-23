<?php

include('../connections/config.php');

// requirements para makapagsend ng email
require('assets/PHPMailer-master/src/PHPMailer.php');
require('assets/PHPMailer-master/src/SMTP.php');
require('assets/PHPMailer-master/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM materials, orders WHERE orders.product_id = materials.id AND orders.id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are order records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if order exist
    if ($count == 1) {

        $id = $row['product_id'];
        $order_id = $row['order_id'];
        $product_id = $row['product_id'];
        $product_name = $row['products'];
        $description = $row['description'];
        $supplier = $row['supplier'];
        $unit = $row['unit'];
        $product_price = $row['product_price'];
        $quantity = $row['qty'];
        $amount = $row['amount_paid'];
        $date_ordered = $row['date_ordered'];
        $status = $row['status'];
        $date_approved = $row['date_approved'];
        $date_received = $row['date_received'];
        $rejection_reason = $row['rejection_reason'];
        $date_rejected = $row['date_rejected'];
        $return_reason = $row['return_reason'];
        $date_returned = $row['date_returned'];
    } else {

        $_SESSION['order-not-found'] = "Order Not Found.";
        header("Location: manage-purchase-orders.php");
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['order-not-found'] = "Order Not Found.";
    header("Location: manage-purchase-orders.php");
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Purchase Order Details</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <link rel="stylesheet" href="../assets/css/style.css" />

</head>

<body class="app">

    <?php $page = 'return';
    include('navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">
                        <!-- alert messages -->
                        <?php
                        if (isset($_SESSION['order-not-received'])) {
                        ?>
                            <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                                <strong> <?php echo $_SESSION['order-not-received']; ?> </strong>
                                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php
                            unset($_SESSION['order-not-received']);
                        }
                        if (isset($_SESSION['order-not-returned'])) {
                        ?>
                            <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                                <strong> <?php echo $_SESSION['order-not-returned']; ?> </strong>
                                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php
                            unset($_SESSION['order-not-returned']);
                        }
                        ?>
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <table class="table text-left">
                                    <thead>
                                        <a href="manage-returns.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
                                        <h5 style="color:#5b99ea; font-weight:bold">Order Details</h5>
                                        <hr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Order ID:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold; text-align:right"><?php echo $order_id; ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Name:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold; text-align:right"><?php echo $product_name; ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Description:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold; text-align:right"><?php echo $description; ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Supplier:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold; text-align:right"><?php echo $supplier; ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Unit:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold; text-align:right"><?php echo $unit; ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Price:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold; text-align:right">₱<?php echo number_format($product_price, 2, '.', ','); ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Quantity:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold; text-align:right">x<?php echo $quantity; ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Total:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p class="text-danger" style="font-weight:bold; text-align:right">₱<?php echo number_format($amount, 2, '.', ','); ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Date Ordered:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold; text-align:right"><?php echo $date = date("M d, Y - h:i a", strtotime($date_ordered)); ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <?php
                                            //check if status is returning
                                            if ($status == 'Returning') {
                                            ?>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold">Order Status:</p>
                                                </td>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold; color:red; text-align:right">Returning</p>
                                                </td>
                                        <tr>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Return Reason:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold; text-align:right"><?php echo $return_reason; ?></p>
                                            </td>
                                        </tr>
                                    <?php
                                            }
                                    ?>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--//app-card-body-->

                        </div>
                        <!--//app-card-->
                    </div>
                </div>
                <!--//row-->

                <hr class="my-4">
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

</body>

</html>