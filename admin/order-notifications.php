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
    <?php $page='order'; include('navbar.php');?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
                <h1 class="app-page-title"><span class="nav-icon"></span>Orders Notifications</h1>
                <hr class="mb-4">
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">

                    <?php
						$sql = "SELECT * FROM materials, orders WHERE materials.name = orders.products AND orders.view_status = '0' ORDER BY orders.id DESC";
                        $result = mysqli_query($conn, $sql);
                        $count = mysqli_num_rows($result);

                        //check if worker record are existing in db
							if ($count > 0) {

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['id'];
                                    $order_id = $row['order_id'];
                                    $status = $row['status'];
                                    $supplier = $row['supplier'];
                                    $products = $row['products'];
                                    $qty = $row['qty'];
                                    $unit = $row['unit'];
                                    $product_price = $row['product_price'];
                                    $amount_paid = $row['amount_paid'];
                                    $date_approved = $row['date_approved'];
                                    $date_received = $row['date_received'];
                                    $date_rejected = $row['date_rejected'];
                                    $rejection_reason = $row['rejection_reason'];
                                    $date_returned = $row['date_returned'];
                                    $return_reason = $row['return_reason'];
                                    $encoder = $row['encoder'];
                                    ?>
                    <div class="app-card app-card-notification shadow-sm mb-4">
                        <div class="app-card-header px-4 py-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-12 col-lg-auto text-center text-lg-start">						        
                                    <img class="profile-image" src="assets/images/payment.jpg" alt="">
                                </div><!--//col-->
                                <div class="col-12 col-lg-auto text-center text-lg-start">
                                    <h2 class="notification-title mb-1"> <?php echo "Your Order ";?> <span class="fw-bold"> <?php echo $order_id; ?></span> <?php echo " is " . $status; ?> </h4>
                                    
                                    <ul class="notification-meta list-inline mb-0">
                                        <li class="list-inline-item"><span class="fw-bold"><?php echo $encoder; ?></li></span>
                                        <li class="list-inline-item">|</li>
                                        <li class="list-inline-item"><span class="fw-bold"><?php echo $supplier; ?></li></span>
                                    </ul>
                            
                                </div><!--//col-->
                            </div><!--//row-->
                        </div><!--//app-card-header-->
                        <div class="app-card-body p-4">
                            <div class="notification-content">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                    <span style="font-size: 1rem"> Order: <span class="fw-bold"> <?php echo $products; ?> </span></span>
                                    <br>
                                    <span style="font-size: 1rem"> Price: <span class="fw-bold"> ₱ <?php echo number_format($product_price, 2, '.', ','); ?></span></span>
                                    <br>
                                    <span style="font-size: 1rem"> Unit: <span class="fw-bold"> <?php echo $qty . " "  . $unit; ?></span></span>
                                    <br>
                                    <span style="font-size: 1rem"> Total Amount:  <span class="fw-bold"> ₱ <?php echo number_format($amount_paid, 2, '.', ','); ?></span></span>
                                    <br>
                                    <span style="font-size: 1rem">  Date Approved: <span class="fw-bold"> <?php if ($date_approved == "0000-00-00 00:00:00") {
                                            echo "Waiting for Approval";
                                        } else {
                                            echo $date = date("M d, Y - h:i a", strtotime($date_approved));
                                        }   
                                    ?></span></span>
                                    <br>
                                    </div>
                                    <div class="col-md-6 col-12">
                                    <span style="font-size: 1rem">  Date Received: <span class="fw-bold"> <?php if ($date_received == "0000-00-00 00:00:00") {
                                            echo "-";
                                        } else {
                                            echo $date = date("M d, Y - h:i a", strtotime($date_received));
                                        }   
                                    ?></span></span>
                                    <br>
                                    <span style="font-size: 1rem"> Date Rejected: <span class="fw-bold"> <?php if ($date_rejected == "0000-00-00 00:00:00") {
                                            echo "-";
                                        } else {
                                            echo $date = date("M d, Y - h:i a", strtotime($date_rejected));
                                        }   
                                    ?></span></span>
                                    <br>
                                    <span style="font-size: 1rem"> Rejection Reason: <span class="fw-bold"> <?php echo $rejection_reason; ?></span></span>
                                    <br>
                                    <span style="font-size: 1rem"> Date Returned <span class="fw-bold"> <?php if ($date_returned == "0000-00-00 00:00:00") {
                                            echo "-";
                                        } else {
                                            echo $date = date("M d, Y - h:i a", strtotime($date_returned));
                                        }   
                                    ?></span></span> 
                                    <br>
                                    <span style="font-size: 1rem"> Return Reason: <span class="fw-bold"> <?php echo $return_reason; ?></span></span>
                                    </div>
                                </div>
                        </div>
                        </div><!--//app-card-body-->
                        <div class="app-card-footer px-4 py-3">
                        </div><!--//app-card-footer-->
				    </div><!--//app-card-->
                    <hr class="my-4">
                    
                    <?php
                            
                        }
                        
                        $sql= "UPDATE orders SET notification_status = '1' WHERE order_id = '$order_id'";
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

    <?php
    

    
    ?>
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