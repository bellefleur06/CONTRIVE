<?php

include('../connections/config.php');

if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Billing Reports</title>

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
    include('engineer-navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <!-- TABS -->
                <nav id="reports-table-tab" class="reports-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
                    <a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="client-reports.php" role="tab" data-target="#client" aria-selected="true">Clients</a>
                    <a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="project-reports.php" role="tab" aria-selected="false">Projects</a>
                    <a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="supplier-reports.php" role="tab" aria-selected="false">Suppliers</a>
                    <a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="worker-reports.php" role="tab" aria-selected="false">Workers</a>
                    <a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="staff-reports.php" role="tab" aria-selected="false">Staffs</a>
                    <a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="purchase-report.php" role="tab" aria-selected="false">Purchase</a>
                    <a class="flex-sm-fill text-sm-center nav-link active" data-bs-toggle="tab" href="billing-report.php" role="tab" aria-selected="false">Billing</a>
                </nav>
                <!-- TABS -->
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Reports</li>
                    <li class="breadcrumb-item active">Billing Reports</li>
                </ol>
                <h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Billing Reports</h1>

                <hr class="mb-4">
                <span style="font-weight:bold">Filter Records By:</span>
                <a href="billing-report.php" class="btn app-btn" style="margin-bottom: 0.5em; border:1px solid #000"><i class="fa fa-calendar"></i> Month and Year</a>
                <a href="billing-report-filter-by-month-only.php" class="btn app-btn" style="margin-bottom: 0.5em; border:1px solid #000"><i class="fa fa-calendar"></i> Month Only</a>
                <a href="billing-report-filter-by-year-only.php" class="btn app-btn" style="margin-bottom: 0.5em; border:1px solid #000"><i class="fa fa-calendar"></i> Year Only</a>
                <a href="billing-report-filter-by-two-dates.php" class="btn app-btn btn-info" style="margin-bottom: 0.5em; color:white"><i class="fa fa-calendar"></i> Between Two Dates</a>
                <hr class="mb-4">

                <form class="settings-form" method="post">
                    <div class="row">
                        <div class="col-md-6 col-12 mb-3">
                            <label for="setting-input-3" class="form-label" style="font-weight:bold">From:</label>
                            <input type="date" name="from_date" class="form-control" required>
                        </div>
                        <div class=" col-md-6 col-12 mb-3">
                            <label for="setting-input-3" class="form-label" style="font-weight:bold">To:</label>
                            <input type="date" name="to_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="submit" class="btn app-btn btn-info" style="color:#fff">Filter</button>
                            <a href="billing-report-filter-by-two-dates.php" class="btn app-btn btn-danger" style="color:#fff">Clear</a>
                        </div>
                    </div>
                </form>

                <?php if (isset($_POST['submit'])) {

                    $from_date = mysqli_real_escape_string($conn, $_POST['from_date']);
                    $to_date = mysqli_real_escape_string($conn, $_POST['to_date']);

                ?>
                    <div class="row g-4 settings-section">
                        <div class="col-12 col-md-12">
                            <div class="app-card app-card-settings shadow-sm p-4">
                                <div class="app-card-body">
                                    <div class="mb-3">
                                        <table id="myTable" class="table app-table-hover mb-0 text-left">
                                            <thead>
                                                <?php
                                                $sql = "SELECT * FROM receivables WHERE total_invoice != '' AND receivable_date_added BETWEEN '$from_date' AND '$to_date' ORDER BY receivable_date_added";
                                                $result = mysqli_query($conn, $sql);
                                                ?>
                                                <tr>
                                                    <th class="cell">Invoice ID</th>
                                                    <th class="cell">Client</th>
                                                    <th class="cell">Invoice Amount</th>
                                                    <th class="cell">Date Issued</th>
                                                    <th class="cell">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_array($result)) { ?>
                                                    <tr>
                                                        <td class="cell" style="padding-top:0.5em">#<?php echo $row['invoice_id']; ?></td>
                                                        <td class="cell" style="padding-top:0.5em"><?php echo $row['client_name']; ?></td>
                                                        <td class="cell" style="padding-top:0.5em">₱<?php echo number_format($row['total_invoice'], 2, '.', ','); ?></td>
                                                        <td class="cell" style="padding-top:0.5em"><?php echo date("M d, Y - h:i a", strtotime($row['receivable_date_added'])); ?></td>
                                                        <td><a href="billing-report-details.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-success" style="color:white"><i class="fa fa-eye"></i> View</a></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--//app-card-body-->

                            </div>
                            <!--//app-card-->
                        </div>
                    </div>

                <?php
                } else {
                ?>
                    <div class="row g-4 settings-section">
                        <div class="col-12 col-md-12">
                            <div class="app-card app-card-settings shadow-sm p-4">
                                <div class="app-card-body">
                                    <div class="mb-3">
                                        <table id="myTable" class="table app-table-hover mb-0 text-left">
                                            <thead>
                                                <?php
                                                $sql = "SELECT * FROM receivables WHERE total_invoice != '' ORDER BY receivable_date_added";
                                                $result = mysqli_query($conn, $sql);
                                                ?>
                                                <tr>
                                                    <th class="cell">Invoice ID</th>
                                                    <th class="cell">Client</th>
                                                    <th class="cell">Invoice Amount</th>
                                                    <th class="cell">Date Issued</th>
                                                    <th class="cell">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_array($result)) { ?>
                                                    <tr>
                                                        <td class="cell" style="padding-top:0.5em">#<?php echo $row['invoice_id']; ?></td>
                                                        <td class="cell" style="padding-top:0.5em"><?php echo $row['client_name']; ?></td>
                                                        <td class="cell" style="padding-top:0.5em">₱<?php echo number_format($row['total_invoice'], 2, '.', ','); ?></td>
                                                        <td class="cell" style="padding-top:0.5em"><?php echo date("M d, Y - h:i a", strtotime($row['receivable_date_added'])); ?></td>
                                                        <td><a href="billing-report-details.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-success" style="color:white"><i class="fa fa-eye"></i> View</a></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--//app-card-body-->

                            </div>
                            <!--//app-card-->
                        </div>
                    </div>
                <?php
                }
                ?>

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

</html>