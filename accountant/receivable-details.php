<?php

include('../connections/config.php');

if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM receivables WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    if ($count == 1) {
    } else {

        $_SESSION['receivable-not-found'] = "Receivable Not Found.";
        header("Location: manage-receivables.php");
    }
}

if ($id == "") {

    $_SESSION['receivable-not-found'] = "Receivable Not Found.";
    header("Location: manage-receivables.php");
}

if (isset($_POST['submit'])) {

    $receivable_id = mysqli_real_escape_string($conn, $_POST['receivable_id']);
    $invoice_id = mysqli_real_escape_string($conn, $_POST['invoice_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $total_amount_receivable = mysqli_real_escape_string($conn, $_POST['total_amount_receivable']);
    $receivable_id = mysqli_real_escape_string($conn, $_POST['receivable_id']);
    $payment_remarks = mysqli_real_escape_string($conn, $_POST['payment']);
    $notification_status = 0;

    if ($amount < $total_amount_receivable) {
        $status = "Partial";
        $activity = "Add Partial Payment of ₱" . $amount . ".00 For Accounts Receivable From " . $row['client_name'];
    } else if ($amount >= $total_amount_receivable) {
        $status = "Paid";
        $activity = "Add Full Payment of ₱" . $amount . ".00 For Accounts Receivable From " . $row['client_name'];
    }

    $diff = $total_amount_receivable - $amount;

    // $activity = "Pay Accounts Payable to " . $row['supplier'];

    if ($diff == 0) {

        $sql = "INSERT INTO payments (receivables_id, price, remaining_amount, payment_status, remarks, notification_status, encoder) VALUES ('$id', '$amount', '$diff', '$status', '$payment_remarks', '$notification_status', '{$_SESSION['username']}')";
        $result = mysqli_query($conn, $sql);

        if ($result == TRUE) {

            $sql = "UPDATE receivables SET total_remaining = '$diff', receivable_status = '$status', receivable_date_received = now() WHERE id = '$receivable_id'";
            $result = mysqli_query($conn, $sql);

            //update last activity date and time
            if ($result = TRUE) {
            } else {
                echo "<script>alert('Error in Updating Last Activity')</script>";
            }

            $sql = "UPDATE invoices SET status = '$status' WHERE invoice_id = '$invoice_id'";
            $result = mysqli_query($conn, $sql);

            //update last activity date and time
            if ($result = TRUE) {
            } else {
                echo "<script>alert('Error in Updating Last Activity')</script>";
            }

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
            
            $_SESSION['add-payment'] = "Payment Added Successfully!";
        } else {

            $_SESSION['failed-to-add'] = "Failed to Add Payment.";
        }
    } else {

        $sql = "INSERT INTO payments (receivables_id, price, remaining_amount, payment_status, remarks, notification_status, encoder) VALUES ('$id', '$amount', '$diff', '$status', '$payment_remarks', '$notification_status', '{$_SESSION['username']}')";
        $result = mysqli_query($conn, $sql);

        if ($result == TRUE) {

            $sql = "UPDATE receivables SET total_remaining = '$diff', receivable_status = '$status' WHERE id = '$receivable_id'";
            $result = mysqli_query($conn, $sql);

            //update last activity date and time
            if ($result = TRUE) {
            } else {
                echo "<script>alert('Error in Updating Last Activity')</script>";
            }

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
            $_SESSION['add-payment'] = "Payment Added Successfully!";
        } else {

            $_SESSION['failed-to-add'] = "Failed to Add Payment.";
        }
    }
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Receivable Details</title>

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


    <!-- used to prevent data to insert again when page is refreshed-->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>

<body class="app">

    <?php $page = 'receivable';
    include('accountant-navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <?php
                if (isset($_SESSION['add-payment'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['add-payment']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['add-payment']);
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
                ?>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <table class="table text-left">
                                        <thead>
                                            <?php
                                            
                                            $id = $_GET['ID'];

                                            $sql = "SELECT * FROM receivables WHERE total_invoice != '' AND id = '$id'";
                                            $result = mysqli_query($conn, $sql);
                                            $row = mysqli_fetch_assoc($result);
                                            
                                            ?>
                                            <a href="manage-receivables.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
                                            <h5 style="color:#5b99ea; font-weight:bold">Receivable Details</h5>
                                            <hr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold">Receivable ID:</p>
                                                </td>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold; text-align:right">#<?php echo $row['invoice_id']; ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold">Client:</p>
                                                </td>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold; text-align:right"><?php echo $row['client_name']; ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold">Amount:</p>
                                                </td>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold; text-align:right">₱<?php echo number_format($row['total_invoice'], 2, '.', ','); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold">Date Created:</p>
                                                </td>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold; text-align:right"><?php echo $date = date("M d, Y - h:i a", strtotime($row['receivable_date_added'])); ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <?php
                                                //check if status is pending
                                                if ($row['receivable_status'] == 'Unpaid') {
                                                ?>
                                                    <td style="padding-top:1.5em">
                                                        <p style="font-weight:bold">Status:</p>
                                                    </td>
                                                    <td style="padding-top:1.5em">
                                                        <p style="font-weight:bold; color:red; text-align:right">Unpaid</p>
                                                    </td>
                                                <?php
                                                    //check if status is approved
                                                } else if ($row['receivable_status'] == 'Partial') {
                                                ?>
                                                    <td style="padding-top:1.5em">
                                                        <p style="font-weight:bold">Status:</p>
                                                    </td>
                                                    <td style="padding-top:1.5em">
                                                        <p style="font-weight:bold; color:blue; text-align:right">Partially Paid</p>
                                                    </td>
                                            <tr>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold">Total Partial Payment:</p>
                                                </td>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold; text-align:right">₱<?php $diff = $row['total_invoice'] - $row['total_remaining'];
                                                                                                    echo number_format($diff, 2, '.', ','); ?> </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold">Total Amount Remaing:</p>
                                                </td>
                                                <td style="padding-top:1.5em">
                                                    <p class="text-danger" style="font-weight:bold; text-align:right">₱<?php echo number_format($row['total_remaining'], 2, '.', ','); ?> </p>
                                                </td>
                                            </tr>
                                        <?php
                                                    //check if status is rejected
                                                } else if ($row['receivable_status'] == 'Paid') {
                                        ?>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold">Status:</p>
                                            </td>
                                            <td style="padding-top:1.5em">
                                                <p style="font-weight:bold; color:green; text-align:right">Fully Paid</p>
                                            </td>
                                            <tr>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold">Total Payment:</p>
                                                </td>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold; text-align:right">₱<?php $diff = $row['total_invoice'] - $row['total_remaining'];
                                                                                                    echo number_format($diff, 2, '.', ','); ?> </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold">Date Paid:</p>
                                                </td>
                                                <td style="padding-top:1.5em">
                                                    <p style="font-weight:bold; text-align:right"><?php echo $date = date("M d, Y - h:i a", strtotime($row['receivable_date_received'])); ?></p>
                                                </td>
                                            </tr>
                                        <?php
                                                }
                                        ?>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <!--//app-card-body-->
                        </div>
                        <!--//app-card-->
                    </div>
                </div>
                <!--//row-->
                <hr class="my-4">

                <h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Payment History</h1>

                <hr class="my-4">

                <?php if ($row['receivable_status'] == 'Paid') : ?>
                    <div class="row g-4 settings-section">
                        <div class="col-12 col-md-12">
                            <div class="app-card app-card-settings shadow-sm p-4">
                                <div class="app-card-body">
                                    <form class="settings-form" method="post">
                                        <table id="myTable" class="table app-table-hover mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">Receivable ID</th>
                                                    <th class="cell">Client</th>
                                                    <th class="cell">Amount Paid</th>
                                                    <th class="cell">Remarks</th>
                                                    <th class="cell">Payment Date</th>
                                                    <th class="cell">Payment Type (Partial or Full)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $id = $_GET['ID'];

                                                $sql = "SELECT * FROM receivables, payments WHERE receivables.id = payments.receivables_id AND receivables.id = '$id' ORDER BY payments.payment_id DESC";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if recievables exist
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {

                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 1em">#<?php echo $row['receivables_id']; ?></td>
                                                            <td class="cell" style="padding-top: 1em"><?php echo $row['client_name']; ?></td>
                                                            <td class="cell" style="padding-top: 1em">₱<?php echo number_format($row['price'], 2, '.', ','); ?>
                                                            <td class="cell" style="padding-top: 1em"><?php echo $row['remarks']; ?>
                                                            <td class="cell" style="padding-top: 1em"><?php echo $date = date("M d, Y - h:i a", strtotime($row['payment_date'])); ?></td>
                                                            </td>
                                                            <?php
                                                            //check if payable status is partially paid
                                                            if ($row['payment_status'] == "Partial") {
                                                            ?>
                                                                <td class="cell" style="padding-top: 1em; font-weight:bold; color:blue">Partial</td>
                                                            <?php
                                                                //check if payable status is paid
                                                            } else if ($row['payment_status'] == "Paid") {
                                                            ?>
                                                                <td class="cell" style="padding-top: 1em; font-weight:bold; color:green">Full</td>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <!--//app-card-body-->
                            </div>
                            <!--//app-card-->
                        </div>
                        <hr class="my-4">

                    </div>

                <?php else : ?>
                    <div class="row g-4 settings-section">
                        <div class="col-12 col-md-4">
                            <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-wallet"></i></span> Add Payment</h1>
                            <div class="app-card app-card-settings shadow-sm p-4">
                                <div class="app-card-body">
                                    <form class="settings-form" method="post">
                                        <div class="mb-3">
                                            <label for="setting-input-3" class="form-label">Amount: </label>
                                            <input type="text" name="amount" class="form-control" placeholder="₱0.00" autocomplete="off" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="setting-input-3" class="form-label">Remarks: </label>
                                            <input type="text" name="payment" class="form-control" autocomplete="off" required>
                                            <input type="hidden" name="total_amount_receivable" value="<?php echo $row['total_remaining'] ?>">
                                            <input type="hidden" name="receivable_id" value="<?php echo $row['id'] ?>">
                                            <input type="hidden" name="invoice_id" value="<?php echo $row['invoice_id'] ?>">
                                        </div>
                                        <button type="submit" name="submit" class="btn app-btn-primary">Add</button>
                                    </form>
                                </div>
                                <!--//app-card-body-->
                            </div>
                        </div>

                        <div class="col-12 col-md-8">
                            <div class="app-card app-card-settings shadow-sm p-4">
                                <div class="app-card-body">
                                    <form class="settings-form" method="post">
                                        <table id="myTable" class="table app-table-hover mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">Receivable ID</th>
                                                    <th class="cell">Client</th>
                                                    <th class="cell">Amount Paid</th>
                                                    <th class="cell">Remarks</th>
                                                    <th class="cell">Payment Date</th>
                                                    <th class="cell">Payment Type (Partial or Full)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $id = $_GET['ID'];

                                                $sql = "SELECT * FROM receivables, payments WHERE receivables.id = payments.receivables_id AND receivables.id = '$id' ORDER BY payments.payment_id DESC";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if recievables exist
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {

                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 1em">#<?php echo $row['receivables_id']; ?></td>
                                                            <td class="cell" style="padding-top: 1em"><?php echo $row['client_name']; ?></td>
                                                            <td class="cell" style="padding-top: 1em">₱<?php echo number_format($row['price'], 2, '.', ','); ?>
                                                            <td class="cell" style="padding-top: 1em"><?php echo $row['remarks']; ?></td>
                                                            <td class="cell" style="padding-top: 1em"><?php echo $date = date("M d, Y - h:i a", strtotime($row['payment_date'])); ?></td>
                                                            </td>
                                                            <?php
                                                            //check if payable status is partially paid
                                                            if ($row['payment_status'] == "Partial") {
                                                            ?>
                                                                <td class="cell" style="padding-top: 1em; font-weight:bold; color:blue">Partial</td>
                                                            <?php
                                                                //check if payable status is paid
                                                            } else if ($row['payment_status'] == "Paid") {
                                                            ?>
                                                                <td class="cell" style="padding-top: 1em; font-weight:bold; color:green">Full</td>
                                                            <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <!--//app-card-body-->
                            </div>
                            <!--//app-card-->
                        </div>
                        <!--//row-->

                        <hr class="my-4">

                    </div>
                    <!--//container-fluid-->
                <?php endif ?>


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