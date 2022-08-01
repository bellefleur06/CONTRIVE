<?php

include('../connections/config.php');

//check if the user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}
$sql = "SELECT * FROM staffs WHERE id = '{$_SESSION['id']}'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Notifications</title>

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
    <?php $page='payment'; include('navbar.php');?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
                <h1 class="app-page-title"><span class="nav-icon"></span>Payment Notifications</h1>
                <hr class="mb-4">
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">

                    <?php
                        $sql = "SELECT * FROM payments, receivables WHERE receivables.id = payments.receivables_id AND payments.view_status = '0' ORDER BY payments.payment_id DESC";
                        $result = mysqli_query($conn, $sql);
                        $count = mysqli_num_rows($result);

                        //check if worker record are existing in db
							if ($count > 0) {

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $payment_id = $row['payment_id'];
                                    $encoder = $row['encoder'];
                                    $client_name = $row['client_name'];
                                    $price = $row['price'];
                                    $remarks = $row['remarks'];
                                    $payment_status = $row['payment_status'];
                                    $total_invoice_amount = $row['total_invoice'];
                                    $total_remaining_amount = $row['remaining_amount'];
                                    $payment_date = $row['payment_date'];
                    ?>
                    <div class="app-card app-card-notification shadow-sm mb-4">
                        <div class="app-card-header px-4 py-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-12 col-lg-auto text-center text-lg-start">						        
                                    <img class="profile-image" src="assets/images/payment.jpg" alt="">
                                </div><!--//col-->
                                <div class="col-12 col-lg-auto text-center text-lg-start">
                                    <h4 class="notification-title mb-1">New Payment Added</h4>
                                    
                                    <ul class="notification-meta list-inline mb-0">
                                        <li class="list-inline-item"><span class="fw-bold"><?php echo $encoder; ?></li></span>
                                    </ul>
                            
                                </div><!--//col-->
                            </div><!--//row-->
                        </div><!--//app-card-header-->
                        <div class="app-card-body p-4">
                            <div class="notification-content">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                        <span style="font-size: 1rem"> Client: <span class="fw-bold"> <?php echo $client_name; ?> </span></span>
                                        <br>
                                        <span style="font-size: 1rem"> Payment Amount: <span class="fw-bold"> ₱ <?php echo number_format($price, 2, '.', ','); ?></span></span>
                                        <br>
                                        <span style="font-size: 1rem"> Total Invoice Amount: <span class="fw-bold"> ₱ <?php echo number_format($total_invoice_amount, 2, '.', ','); ?></span></span>
                                        <br>
                                        <span style="font-size: 1rem"> Remaining Invoice Amount: <span class="fw-bold"> ₱ <?php echo number_format($total_remaining_amount, 2, '.', ','); ?></span></span>
                                        </div>
                                        <div class="col-md-6 col-12">
                                        <span style="font-size: 1rem"> Remarks: <span class="fw-bold"> <?php echo $remarks; ?></span></span>
                                        <br>
                                        <span style="font-size: 1rem"> Payment Status: <span class="fw-bold"> <?php echo $payment_status; ?></span></span>
                                        <br>
                                        <span style="font-size: 1rem"> Payment Date: <span class="fw-bold"> <?php echo $date = date("M d, Y - h:i a", strtotime($payment_date)); ?></span></span>
                                        <br>
                                        </div>
                                    </div>
                            </div>
                        </div><!--//app-card-body-->
				    </div><!--//app-card-->
                    <hr class="my-4">

                    
                    <?php
                            }

                            $sql= "UPDATE payments SET notification_status = '1' WHERE payment_id = '$payment_id'";
                            $result = mysqli_query($conn, $sql);

                        } else {
                    ?>
                        <div class="app-card app-card-notification shadow-sm mb-4">
                            <div class="app-card-header px-4 py-3">
                                <div class="row g-3 align-items-center">
                                    <div class="col-12 col-lg-auto text-center text-lg-start">
                                        <h2 class="my-3">No New Notification...</h2>
                                    </div><!--//col-->
                                </div><!--//row-->
                            </div><!--//app-card-header-->
                        </div><!--//app-card-->
                        <hr class="my-4">
                    <?php
                        }
                    ?>
                    </div>
                </div>
            </div>
            <!--//row-->
        </div>
    </div>

    <!-- Javascript -->
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script>

</body>

<script>
    setTimeout(function() {
        document.getElementById("alert").style.display = "none";
    }, 3000);
</script>

</html>