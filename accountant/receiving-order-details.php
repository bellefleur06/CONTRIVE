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

if (isset($_POST['receive'])) {
    $payable_id = "#" . rand(000000, 999999);
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $product = mysqli_real_escape_string($conn, $_POST['product']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $qty = mysqli_real_escape_string($conn, $_POST['qty']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $date_ordered = mysqli_real_escape_string($conn, $_POST['date_ordered']);
    $status = "Unpaid";
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    // $supplier = mysqli_real_escape_string($conn, $_POST['supplier']);
    $activity = "Receive Order From " . $supplier . " - " . $qty . " " . $unit . " of " . $product;

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $order_status = "Received";

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = "true";
    $mail->SMTPSecure = "tls";
    $mail->Port = "587";
    $mail->Username = "contrivekcs@gmail.com"; //ito yung gamit kong email pang send
    $mail->Password = "contrivekcs"; //password ng email ko
    $mail->Subject = "Order Received!"; //subject ng email natin
    $mail->setFrom("contrivekcs@gmail.com"); //kung kanino galing yung email
    $mail->isHTML(true); //naka true para madesignan yung email body
    $mail->Body = "<p>Order $order_id - $product_quantity$unit of $product_name has been received successfully.</p>"; // dito sa body pwede mo designan ng html at css yung body ng email natin
    $mail->addAddress($email); //kung kanino isesend yung email, wala nang problema dito

    if ($mail->send()) {

        $sql = "INSERT INTO payables (payable_id, order_id, product_id, products, product_price, qty, amount_paid, total_amount, date_ordered, date_received, status, user_id) VALUES ('$payable_id', '$order_id', '$product_id', '$product', '$product_price', '$qty', '$amount', '$amount', '$date_ordered', now(), '$status', '$user_id')";
        $result = mysqli_query($conn, $sql);

        if ($result == TRUE) {

            $sql = "INSERT INTO logs SET username = '{$_SESSION['username']}' , log_time = now(), activity = '$activity'";
            $result = mysqli_query($conn, $sql);

            //insert info into audit trail
            if ($result = TRUE) {
            } else {
                echo "<script>alert('Error in Recording Logs')</script>";
            }

            $sql = "UPDATE orders SET status = '$order_status', date_received = now() WHERE id = $id";
            $result = mysqli_query($conn, $sql);

            if ($result = TRUE) {

                $sql = "SELECT * FROM materials WHERE id = $product_id";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                //check if there are worker records
                if ($result == TRUE) {

                    $count = mysqli_num_rows($result);

                    //check if worker exist
                    if ($count == 1) {
                        $stocks = $row['stocks'];
                    }
                }
            }

            $new_stocks = $stocks + $qty;

            $sql = "UPDATE materials SET stocks = '$new_stocks' WHERE id = $product_id";
            $result = mysqli_query($conn, $sql);

            //update materials stock
            if ($result = TRUE) {
            } else {
                echo "<script>alert('Error in Updating Stocks')</script>";
            }

            $sql = "UPDATE staffs SET last_activity = now() WHERE id = '{$_SESSION['id']}'";
            $result = mysqli_query($conn, $sql);

            //update last activity date and time
            if ($result = TRUE) {
            } else {
                echo "<script>alert('Error in Updating Last Activity')</script>";
            }

            $activity = $qty . " " . $unit . " of " . $product . " added to stocks";

            $sql = "INSERT INTO logs SET username = '{$_SESSION['username']}' , log_time = now(), activity = '$activity'";
            $result = mysqli_query($conn, $sql);

            //insert info into audit trail
            if ($result = TRUE) {
            } else {
                echo "<script>alert('Error in Recording Logs')</script>";
            }

            $_SESSION['order-received'] = "Order Received Successfully!";
            header("Location: purchase-order-history.php");
        } else {
            $_SESSION['order-not-received'] = "Order Not Received.";
            header("Location: purchase-order-details.php?ID=$id");
        }
    }
    $mail->smtpClose();
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
    <link id="theme-style" rel="stylesheet" href="../assets/css/portal.css">
    <link rel="stylesheet" href="../assets/css/style.css" />

</head>

<body class="app">

    <?php $page = 'purchase';
    include('accountant-navbar.php'); ?>

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
                                        <a href="manage-receivings.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
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
                                            //check if status is on delivery
                                            if ($status == 'On Delivery') {
                                            ?>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold">Order Status:</p>
                                                </td>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold; color:orange; text-align:right">On Delivery</p>
                                                </td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php if ($status == 'On Delivery') : ?>
                                    <form class="settings-form" method="post">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                        <input type="hidden" name="product" value="<?php echo $row['products']; ?>">
                                        <input type="hidden" name="supplier" value="<?php echo $row['supplier']; ?>">
                                        <input type="hidden" name="email" value="<?php echo $row['email_address']; ?>">
                                        <input type="hidden" name="price" value="<?php echo $row['product_price']; ?>">
                                        <input type="hidden" name="qty" value="<?php echo $row['qty']; ?>">
                                        <input type="hidden" name="amount" value="<?php echo $row['amount_paid']; ?>">
                                        <input type="hidden" name="date_ordered" value="<?php echo $row['date_ordered']; ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>">
                                        <button type="submit" name="receive" class="btn app-btn-primary"> Receive Order</button>
                                        <a href="return-order.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-danger" style="color:white"> Return Order</a>
                                    </form>
                                <?php endif ?>
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