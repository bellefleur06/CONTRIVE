<?php

include('../connections/config.php');

error_reporting(0);

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

//check if add button is clicked
if (isset($_POST['return'])) {

    $return_reason =  mysqli_real_escape_string($conn, $_POST['return_reason']);
    $status = "Returning";
    $activity = "Receive Order To " . $supplier . " - " . $qty . " " . $unit . " of " . $product;

    $sql = "UPDATE orders SET status = '$status', return_reason = '$return_reason', date_returned = now() WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    //check if insert process is true
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

        $_SESSION['order-returned'] = "Order Return Requested Successfully!";
        header("Location: purchase-order-history.php");
    } else {

        $_SESSION['order-not-returned'] = "Order Not Returned.";
        header("Location: purchase-order-details.php?ID=$id");
    }
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Return Order</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

    <!-- JQuery -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="assets/plugins/jquery-ui-1.13.0/jquery-ui.css">
    <script src="assets/plugins/jquery-ui-1.13.0/external/jquery/jquery.js"></script>
    <script src="assets/plugins/jquery-ui-1.13.0/jquery-ui.js"></script>

    <link rel="stylesheet" href="assets/plugins/chosen/chosen.min.css">
    <script src="assets/plugins/chosen/chosen.jquery.min.js"></script>

    <!-- used to prevent data to insert again when page is refreshed-->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>

<body class="app">

    <?php $page = 'receive';
    include('navbar.php'); ?>

    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="manage-receivings.php">Manage Receiving</a></li>
                    <li class="breadcrumb-item active">Return Order</li>
                </ol>
                <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-building"></i></span> Return Order</h1>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['failed-to-add'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-add']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['failed-to-add']);
                }
                if (isset($_SESSION['project-already-exist'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['project-already-exist']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['project-already-exist']);
                }
                ?>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">
                        <div class="app-card app-card-settings shadow-sm p-4">

                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Return Reason: </label>
                                        <input type="text" name="return_reason" class="form-control" id="setting-input-3" autocomplete="off" required>
                                    </div>
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                    <input type="hidden" name="product" value="<?php echo $row['products']; ?>">
                                    <input type="hidden" name="supplier" value="<?php echo $row['supplier']; ?>">
                                    <input type="hidden" name="price" value="<?php echo $row['product_price']; ?>">
                                    <input type="hidden" name="qty" value="<?php echo $row['qty']; ?>">
                                    <input type="hidden" name="amount" value="<?php echo $row['amount_paid']; ?>">
                                    <input type="hidden" name="date_ordered" value="<?php echo $row['date_ordered']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>">
                                    <button type="submit" name="return" class="btn app-btn-primary">Return Order</button>
                                    <a href="receiving-order-details.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-info float-end" style="color:#fff"><i class="fas fa-arrow-left"></i> Go Back</a>
                                </form>
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

<script>
    setTimeout(function() {
        document.getElementById("alert").style.display = "none";
    }, 3000);
</script>

</html>