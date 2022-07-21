<?php

include('../connections/config.php');

// requirements para makapagsend ng email
require('assets/PHPMailer-master/src/PHPMailer.php');
require('assets/PHPMailer-master/src/SMTP.php');
require('assets/PHPMailer-master/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

//check if page is not forcefully accessed
if (isset($_GET['checkout']) == "") {

    // $_SESSION['staff-not-found'] = "Staff Not Found.";
    header("Location: add-purchase-orders.php");
}

if (isset($_POST['submit'])) {

    foreach ($_POST['order_id'] as $i => $order_id) {

        $order_id = mysqli_real_escape_string($conn, $_POST['order_id'][$i]);
        $product_id = mysqli_real_escape_string($conn, $_POST['product_id'][$i]);
        $product_name = mysqli_real_escape_string($conn, $_POST['product_name'][$i]);
        $product_price = mysqli_real_escape_string($conn, $_POST['product_price'][$i]);
        $product_quantity = mysqli_real_escape_string($conn, $_POST['product_quantity'][$i]);
        $supplier = mysqli_real_escape_string($conn, $_POST['supplier'][$i]);
        $unit = mysqli_real_escape_string($conn, $_POST['unit'][$i]);
        $total_price = mysqli_real_escape_string($conn, $_POST['total_price'][$i]);
        $email = mysqli_real_escape_string($conn, $_POST['email'][$i]);
        $unit = mysqli_real_escape_string($conn, $_POST['unit'][$i]);
        $activity = "Order " . $product_quantity . " " . $unit . " of " . $product_name . " from " . $supplier;
        $status = "Pending";
        $notification_status = "0";
        $view_status = "0";
        $token = md5($email) . rand(10, 9999);
        $link = "<a href='http://contrive.epizy.com/order-requests.php?key=" . $email . "&token=" . $token . "'>Click To View Orders</a>";
        // $link = "<a href='http://localhost/contrive-kcs/order-requests.php?key=" . $email . "&token=" . $token . "'>Click To View Orders</a>";

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = "true";
        $mail->SMTPSecure = "tls";
        $mail->Port = "587";
        $mail->Username = "contrivekcs@gmail.com"; //ito yung gamit kong email pang send
        $mail->Password = "xffsqsrowvxhrona"; //password ng app
        $mail->Subject = "Order Request!"; //subject ng email natin
        $mail->setFrom("contrivekcs@gmail.com"); //kung kanino galing yung email
        $mail->isHTML(true); //naka true para madesignan yung email body
        $mail->Body = "<h3>Request for Purchase of Order</h3><p>$order_id - $product_quantity$unit of $product_name</p><p>$link</p>"; // dito sa body pwede mo designan ng html at css yung body ng email natin
        $mail->addAddress($email); //kung kanino isesend yung email, wala nang problema dito

        if ($mail->send()) {

            $sql = "INSERT INTO orders (order_id, product_id, products, product_price, qty, amount_paid, status, email_address, token, notification_status, view_status, encoder) VALUES ('$order_id','$product_id','$product_name','$product_price','$product_quantity','$total_price','$status','$email','$token', '$notification_status', 'view_status', '{$_SESSION['username']}')";
            $result = mysqli_query($conn, $sql);

            if ($result == TRUE) {

                $sql = "UPDATE staffs SET last_activity = now() WHERE id = '{$_SESSION['id']}'";
                $result = mysqli_query($conn, $sql);

                //update last activity date and time
                if ($result = TRUE) {
                } else {
                    echo "<script>alert('Error in Updating Last Activity')</script>";
                }

                $sql = "INSERT INTO logs SET username = '{$_SESSION['username']}' , log_time = now(), activity = '$activity'";
                $result = mysqli_query($conn, $sql);

                //insert info into audit trail
                if ($result = TRUE) {
                } else {
                    echo "<script>alert('Error in Recording Logs')</script>";
                }

                $sql = "DELETE FROM cart";
                $result = mysqli_query($conn, $sql);

                if ($result == TRUE) {

                    $_SESSION['add-orders'] = "Items Ordered Successfully";
                    header("Location: manage-purchase-orders.php");
                } else {
                    $_SESSION['failed-to-add'] = "Failed To Order Your Items.";
                    header("Location: manage-purchase-orders.php");
                }
            } else {
                $_SESSION['failed-to-add'] = "Failed To Order Your Items.";
                header("Location: manage-purchase-orders.php");
            }
        }
        $mail->smtpClose();
    }
}


?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Checkout</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link id="theme-style" rel="stylesheet" href="dataTables/jquery.dataTables.min.css">
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <link rel="stylesheet" href="../assets/css/style.css" />

    <!-- JQuery -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <!-- used to prevent data to insert again when page is refreshed-->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>

<body class="app">

    <?php $page = 'purchase';
    include('navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <table class="table text-left">
                                        <?php
                                        $sql = "SELECT * FROM suppliers, cart, materials WHERE materials.id = cart.product_id AND materials.supplier = suppliers.name";
                                        $result = mysqli_query($conn, $sql);
                                        $count = mysqli_num_rows($result);

                                        //check if worker record are existing in db
                                        if ($count > 0) {

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $id = $row['product_id'];
                                                $order_id = $row['order_id'];
                                                $product_name = $row['product_name'];
                                                $description = $row['description'];
                                                $supplier = $row['supplier'];
                                                $unit = $row['unit'];
                                                $product_price = $row['product_price'];
                                                $quantity = $row['qty'];
                                                $email = $row['email'];
                                                $amount = $row['total_price'];

                                        ?>
                                                <table class="table text-left">
                                                    <thead>
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
                                                                <input type="hidden" name="product_id[]" value="<?php echo $id; ?>">
                                                                <input type="hidden" name="order_id[]" value="<?php echo $order_id; ?>">
                                                                <input type="hidden" name="email[]" value="<?php echo $email; ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold">Name:</p>
                                                            </td>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold; text-align:right"><?php echo $product_name; ?></p>
                                                                <input type="hidden" name="product_name[]" value="<?php echo $product_name; ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold">Description:</p>
                                                            </td>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold; text-align:right"><?php echo $description; ?></p>
                                                                <input type="hidden" name="" value="<?php echo $description; ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold">Supplier:</p>
                                                            </td>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold; text-align:right"><?php echo $supplier; ?></p>
                                                                <input type="hidden" name="supplier[]" value="<?php echo $supplier; ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold">Unit:</p>
                                                            </td>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold; text-align:right"><?php echo $unit; ?></p>
                                                                <input type="hidden" name="unit[]" value="<?php echo $unit; ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold">Price:</p>
                                                            </td>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold; text-align:right">₱<?php echo number_format($product_price, 2, '.', ','); ?></p>
                                                                <input type="hidden" name="product_price[]" value="<?php echo $product_price; ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold">Quantity:</p>
                                                            </td>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold; text-align:right">x<?php echo $quantity; ?></p>
                                                                <input type="hidden" name="product_quantity[]" value="<?php echo $quantity; ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top:1.5em">
                                                                <p style="font-weight:bold">Total:</p>
                                                            </td>
                                                            <td style="padding-top:1.5em">
                                                                <p class="text-danger" style="font-weight:bold; text-align:right">₱<?php echo number_format($amount, 2, '.', ','); ?></p>
                                                                <input type="hidden" name="total_price[]" value="<?php echo $amount; ?>">
                                                            </td>
                                                        </tr>
                                                </table>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td class="text-center" style="padding-bottom:1.25em; font-weight: bold; font-size:1.5em; color:red">No Orders Found!</td>
                                            </tr>
                                            </tbody>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                    <button class="btn btn-primary" type="submit" name="submit" style="color:white; float:right"><i class="fas fa-credit-card"></i> Checkout</button>
                                    <a href="add-purchase-orders.php" class="btn app-btn btn-info" style="color:white"><i class="fa fa-shopping-cart"></i> Contine Shopping</a>
                                </form>
                            </div>
                            <!--//app-card-body-->
                        </div>
                        <!--//app-card-->
                    </div>
                </div>
                <!--//row-->
                <hr class="mb-4">
            </div>
            <!--//container-fluid-->
        </div>
        <!--//app-content-->
    </div>
    <!--//app-wrapper-->

    <!-- fetch material details script -->
    <script>
        function fetch_select(val) {
            $.ajax({
                url: "fetch-material.php",
                type: "POST",
                data: {
                    "get_option": val
                },
                dataType: "JSON",
                success: function(data) {
                    $('#name').val((data[0].name));
                    $('#category_id').val((data[0].category_id));
                    $('#category_name').val((data[0].category_name));
                    $('#description').val((data[0].description));
                    $('#unit').val((data[0].unit));
                    $('#stocks').val((data[0].stocks));
                    $('#price').val((data[0].price));
                }

            });
        }
    </script>

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

<script>
    setTimeout(function() {
        document.getElementById("alert").style.display = "none";
    }, 3000);
</script>

</html>