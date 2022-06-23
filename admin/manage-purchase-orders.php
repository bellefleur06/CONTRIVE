<?php

include('../connections/config.php');

//check if the user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Manage Purchase Orders</title>

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
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Manage Purchase Orders</li>
                </ol>
                <h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Manage Purchase Orders</h1>
                <a href="add-purchase-orders.php" class="btn app-btn-primary"><i class=" fa fa-plus"></i> New Order</a>
                <a href="purchase-order-history.php" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-list"></i> Purchase History</a>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['add-orders'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['add-orders']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['add-orders']);
                }
                if (isset($_SESSION['failed-to-add'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-add']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['failed-to-add']);
                }
                if (isset($_SESSION['order-not-found'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['order-not-found']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['order-not-found']);
                }
                ?>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form">
                                    <div class="mb-3">
                                        <table id="myTable" class="table app-table-hover mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">Order ID</th>
                                                    <th class="cell">Order</th>
                                                    <th class="cell" style="width:12%">Price</th>
                                                    <th class="cell">Quantity</th>
                                                    <th class="cell" style="width:12%">Total</th>
                                                    <th class="cell">Date Ordered</th>
                                                    <th class="cell">Status</th>
                                                    <th class="cell">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM materials, orders WHERE orders.product_id = materials.id AND status != 'On Delivery' AND status != 'Received' AND status != 'Returning' AND status != 'Returned' ORDER BY orders.id DESC";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if orders exist
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $id = $row['id'];
                                                        $order_id = $row['order_id'];
                                                        $products = $row['products'];
                                                        $description = $row['description'];
                                                        $unit = $row['unit'];
                                                        $supplier = $row['supplier'];
                                                        $product_price = $row['product_price'];
                                                        $qty = $row['qty'];
                                                        $amount_paid = $row['amount_paid'];
                                                        $date_ordered = $row['date_ordered'];
                                                        $date = date("M d, Y", strtotime($date_ordered));
                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 0.5em"><?php echo $order_id; ?></td>
                                                            <td class="cell" style="padding-top: 0.5em">
                                                                <p>Name: <b><?php echo $products; ?></b></p>
                                                                <p><small>Description: <b><?php echo $description; ?></b></small></p>
                                                                <p><small>Supplier: <b><?php echo $supplier; ?></b></small></p>
                                                            </td>
                                                            <td class="cell" style="padding-top: 0.5em">₱ <?php echo number_format($row['product_price'], 2, '.', ',') ?></td>
                                                            <td class="cell" style="padding-top: 0.5em"><?php echo $qty . " " . $unit; ?></td>
                                                            <td class="cell" style="padding-top: 0.5em">₱ <?php echo number_format($row['amount_paid'], 2, '.', ','); ?></td>
                                                            <td class="cell" style="padding-top: 0.5em"><?php echo $date; ?></td>
                                                            <?php
                                                            //check if status is pending
                                                            if ($row['status'] == "Pending") {
                                                            ?>
                                                                <td class="cell" style="padding-top: 1em; font-weight:bold; color:blue">Order Pending</td>
                                                            <?php
                                                                //check if status is rejected
                                                            } else if ($row['status'] == "Rejected") {
                                                            ?>
                                                                <td class="cell" style="padding-top: 1em; font-weight:bold; color:red">Order Rejected</td>
                                                            <?php
                                                            }
                                                            ?>
                                                            <td><a href="purchase-order-details.php?ID=<?php echo $id; ?>" class="btn app-btn btn-success" style="color:white"><i class="fa fa-eye"></i> View</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    // echo "<script>alert('No Orders Found!')</script>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
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