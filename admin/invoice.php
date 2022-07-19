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
    <title>CONTRIVE | Billing</title>

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

    <?php $page = 'invoice';
    include('navbar.php'); ?>

    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Billing</li>
                </ol>
                <h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Billing</h1>

                <hr class="mb-4">
                <?php
                if (isset($_SESSION['create-invoice'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['create-invoice']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['create-invoice']);
                }
                if (isset($_SESSION['invoice-not-found'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['invoice-not-found']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['invoice-not-found']);
                }
                ?>
                <div class="row g-3 mb-4 align-items-center justify-content-between">
                    <!-- <div class="col-auto">
                        <a href="add-invoice.php" class="btn app-btn-primary"><i class=" fa fa-plus"></i> New Invoice</a>
                    </div>
                    <div class="col-auto">
                        <div class="page-utilities">
                            <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                                <div class="col-auto">

                                    <select class="form-select w-auto">
                                        <option selected value="option-1">All</option>
                                        <option value="option-2">This week</option>
                                        <option value="option-3">This month</option>
                                        <option value="option-4">Last 3 months</option>

                                    </select>
                                </div> -->
                                <!-- <div class="col-auto">
                                    <a class="btn app-btn-secondary" href="#"><i class="fas fa-download"></i> Download CSV</a>
                                </div> -->
                            <!-- </div> -->
                            <!--//row-->
                        <!-- </div> -->
                        <!--//table-utilities-->
                    <!-- </div> -->
                    <!--//col-auto-->
                </div>
                <!--//row-->

                <!-- <nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
                    <a class="flex-sm-fill text-sm-center nav-link active" id="orders-all-tab" data-bs-toggle="tab" href="#orders-all" role="tab" aria-controls="orders-all" aria-selected="true">All</a>
                    <a class="flex-sm-fill text-sm-center nav-link" id="orders-paid-tab" data-bs-toggle="tab" href="#orders-paid" role="tab" aria-controls="orders-paid" aria-selected="false">Paid</a>
                    <a class="flex-sm-fill text-sm-center nav-link" id="orders-pending-tab" data-bs-toggle="tab" href="#orders-pending" role="tab" aria-controls="orders-pending" aria-selected="false">Pending</a>
                </nav> -->

                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form">
                                    <div class="mb-3">
                                        <table id="myTable" class="table app-table-hover mb-0 text-center">
                                            <thead>
                                                <tr>
                                                    <th class="cell">Invoice</th>
                                                    <th class="cell">Client</th>
                                                    <th class="cell">Invoice Amount</th>
                                                    <th class="cell">Issue Date</th>
                                                    <th class="cell">Due Date</th>
                                                    <th class="cell">Status</th>
                                                    <th class="cell">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM staffs, clients, projects, invoices WHERE staffs.id = projects.engineer_id AND clients.name = projects.client_name AND invoices.project_id = projects.id AND invoices.total_invoice != '' ORDER by invoice_id";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if clients exist
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $invoice_id = $row['invoice_id'];
                                                        $client_name = $row['client_name'];
                                                        $date_created = $row['date_created'];
                                                        $due_date = $row['due_date'];
                                                        $total_invoice = $row['total_invoice'];
                                                        $status = $row['status'];
                                                ?>

                                                        <tr>
                                                            <td class="cell" style="padding-top:0.5em">#<?php echo $invoice_id; ?></td>
                                                            <td class="cell" style="padding-top:0.5em"><?php echo $client_name; ?></td>
                                                            <td class="cell" style="padding-top:0.5em">₱<?php echo number_format($total_invoice, 2, '.', ','); ?></td>
                                                            <td class="cell" style="padding-top:0.5em"><?php echo date("M d, Y", strtotime($date_created)); ?></td>
                                                            <td class="cell" style="padding-top:0.5em"><?php echo date("M d, Y", strtotime($due_date)); ?></td>
                                                            <?php
                                                            //check if status is pending
                                                            if ($status == "Unpaid") {
                                                            ?>
                                                                <td class="cell" style="padding-top:0.5em; font-weight:bold; color:blue">Unpaid</td>

                                                            <?php } else if ($status == "Paid") {
                                                            ?>
                                                                <td class="cell" style="padding-top:0.5em; font-weight:bold; color:green">Paid</td>

                                                            <?php
                                                            }
                                                            ?>
                                                            <td class="cell" style="padding-top:0.5em"><a href="invoice-details.php?ID=<?php echo $invoice_id; ?>" class="btn app-btn btn-success" style="color:white"><i class="fa fa-eye"></i> View</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<script>alert('No Invoices Found!')</script>";
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