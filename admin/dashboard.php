<?php

include('../connections/config.php');

if (!isset($_SESSION['username'])) {

  header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>CONTRIVE | Admin Dashboard</title>

  <!-- Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link id="theme-style" rel="stylesheet" href="dataTables/jquery.dataTables.min.css">
  <!-- FontAwesome JS-->
  <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="shortcut icon" href="../assets/images/icon.ico">

  <!-- Chart JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="app">

  <?php $page = 'dashboard';
  include('navbar.php'); ?>

  <div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
      <div class="position-relative mb-3">
        <div class="row g-3 justify-content-between">
          <div class="col-auto">
            <h1 class="app-page-title mb-0">Dashboard</h1>
          </div>
          <div class="col-auto">
            <!-- <div class="page-utilities">
              <select class="form-select form-select-sm w-auto">
                <option selected value="option-1">This Month</option>
                <option value="option-2">Monthly</option>
                <option value="option-3">Yearly</option>
              </select>
            </div> -->
            <!--//page-utilities-->
          </div>
        </div>
      </div>


      <div class="row g-4 mb-3">

        <div class="col-6 col-lg-3">
          <a href="manage-clients.php">
            <div class="app-card app-card-stat shadow-sm h-100">
              <br />
              <span class="nav-icon"><i class="fa fa-user fa-5x text-success"></i></span>
              <div class="app-card-body p-3 p-lg-4">
                <?php

                $sql = "SELECT * FROM clients";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                ?>
                <h4 class="stats-type mb-1">Clients</h4>
                <div class="stats-figure"><?php echo $count; ?></div>
              </div>
              <!--//app-card-body-->
            </div>
          </a>
          <!--//app-card-->
        </div>

        <div class="col-6 col-lg-3">
          <a href="invoice.php">
            <div class="app-card app-card-stat shadow-sm h-100">
              <br />
              <span class="nav-icon"><i class="fas fa-file-invoice fa-5x text-success"></i></span>
              <div class="app-card-body p-3 p-lg-4">
                <?php

                $sql = "SELECT * FROM invoices WHERE total_invoice != ''";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                ?>
                <h4 class="stats-type mb-1">Invoices</h4>
                <div class="stats-figure"><?php echo $count; ?></div>
              </div>
              <!--//app-card-body-->
            </div>
          </a>
          <!--//app-card-->
        </div>

        <!--//col-->
        <div class="col-6 col-lg-3">
          <a href="manage-projects.php">
            <div class="app-card app-card-stat shadow-sm h-100">
              <br />
              <span class="nav-icon"><i class="fa fa-building fa-5x text-success"></i></span>
              <div class="app-card-body p-3 p-lg-4">
                <?php

                $sql = "SELECT * FROM projects";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                ?>
                <h4 class="stats-type mb-1">Projects</h4>
                <div class="stats-figure"><?php echo $count; ?></div>
              </div>
              <!--//app-card-body-->
            </div>
          </a>
          <!--//app-card-->
        </div>
        <!--//col-->
        <div class="col-6 col-lg-3">
          <a href="manage-workers.php">
            <div class="app-card app-card-stat shadow-sm h-100">
              <br />
              <span class="nav-icon"><i class="fa fa-users fa-5x text-success"></i></span>
              <div class="app-card-body p-3 p-lg-4">
                <?php

                $sql = "SELECT * FROM workers";
                $result = mysqli_query($conn, $sql);
                $workercount = mysqli_num_rows($result);

                $sql1 = "SELECT * FROM staffs";
                $result1 = mysqli_query($conn, $sql1);
                $staffcount = mysqli_num_rows($result1);

                $count = $workercount + $staffcount;

                ?>
                <h4 class="stats-type mb-1">Employees</h4>
                <div class="stats-figure"><?php echo $count; ?></div>
              </div>
              <!--//app-card-body-->
            </div>
          </a>
          <!--//app-card-->
        </div>
        <!--//col-->
      </div>
      <!-- First Row -->

      <!-- Second Row -->
      <div class="row g-4 mb-1">
        <div class="col-6 col-lg-3">
          <a href="manage-suppliers.php">
            <div class="app-card app-card-stat shadow-sm h-100">
              <br />
              <span class="nav-icon"><i class="fa fa-store fa-5x text-success"></i></span>
              <div class="app-card-body p-3 p-lg-4">
                <?php

                $sql = "SELECT * FROM suppliers";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                ?>
                <h4 class="stats-type mb-1">Suppliers</h4>
                <div class="stats-figure"><?php echo $count; ?></div>
              </div>
              <!--//app-card-body-->
            </div>
          </a>
          <!--//app-card-->
        </div>
        <!--//col-->

        <div class="col-6 col-lg-3">
          <a href="invoice.php">
            <div class="app-card app-card-stat shadow-sm h-100">
              <br />
              <span class="nav-icon"><i class="fas fa-file-invoice-dollar fa-5x text-success"></i></span>
              <div class="app-card-body p-3 p-lg-4">
                <?php

                $sql = "SELECT * FROM invoices WHERE total_invoice != '' AND status = 'Paid'";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                ?>
                <h4 class="stats-type mb-1">Paid Bills</h4>
                <div class="stats-figure"><?php echo $count; ?></div>
              </div>
              <!--//app-card-body-->
            </div>
          </a>
          <!--//app-card-->
        </div>
        <!--//col-->

        <div class="col-6 col-lg-3">
          <a href="invoice.php">
            <div class="app-card app-card-stat shadow-sm h-100">
              <br />
              <span class="nav-icon"><i class="fas fa-clock fa-5x text-success"></i></span>
              <div class="app-card-body p-3 p-lg-4">
                <?php

                $sql = "SELECT * FROM invoices WHERE total_invoice != '' AND status = 'Pending'";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                ?>
                <h4 class="stats-type mb-1">Pending Bills</h4>
                <div class="stats-figure"><?php echo $count ?></div>
              </div>
              <!--//app-card-body-->
            </div>
          </a>
          <!--//app-card-->
        </div>
        <!--//col-->

        <div class="col-12 col-lg-12">
          <div class="app-card app-card-stat shadow-sm h-100">
            <br />
            <div class="app-card-body p-3 p-lg-4">
              <div class="row">
                <div class="col-12 col-lg-4 mb-4">
                  <div class="p-3" style="background-color:#5b99ea">
                    <?php
                    $sql = "SELECT SUM(price) as total_income FROM payments";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <h1 class="app-page-title" style="color:white;">
                      ₱ <?php echo number_format($row['total_income'], 2, '.', ','); ?>
                    </h1>
                    <hr class="mb-2" style="background-color:white">
                    <h4 class="stats-type mb-1" style="color:white">Net Worth</h4>
                    </h4>
                    <div class="container mt-4">
                      <form class="settings-form" method="post">
                        <table class="table text-center">
                          <tbody>
                            <?php
                            $sql = "SELECT SUM(price) as daily_income FROM payments WHERE date(payment_date) = CURRENT_DATE";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            ?>
                            <tr>
                              <td class="cell w-100">
                                <label for="setting-input-2" class="form-label" style="color:white; text-align:center;"><b>
                                    ₱ <?php echo number_format($row['daily_income'], 2, '.', ','); ?>
                                  </b></label>
                              </td>
                              <td class="cell">
                                <label for="setting-input-2" class="form-label" style="color:white; text-align:center;">Income Today</label>
                              </td>
                            </tr>
                            <tr>
                              <?php
                              $sql = "SELECT SUM(price) as monthly_income FROM payments WHERE month(payment_date) = MONTH(CURRENT_DATE)";
                              $result = mysqli_query($conn, $sql);
                              $row = mysqli_fetch_assoc($result);
                              ?>
                              <td class="cell w-100">
                                <label for="setting-input-2" class="form-label" style="color:white; text-align:center;"><b>
                                    ₱ <?php echo number_format($row['monthly_income'], 2, '.', ','); ?>
                                  </b></label>
                              </td>
                              <td class="cell">
                                <label for="setting-input-2" class="form-label" style="color:white; text-align:center;">Income This Month</label>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </form>
                    </div>
                  </div>
                </div>
                <div class=" col-12 col-lg-8 mb-4">
                  <div class="p-3" style="background-color:#5b99ea">
                    <?php

                    $year = date('Y');

                    ?>
                    <h1 class="app-page-title" style="text-align:left; color:white; margin-bottom:none">Monthly Sales of <?php echo $year; ?></h1>
                    <div>
                      <canvas id="myChart"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--//app-card-body-->
          </div>
          <!--//app-card-->
        </div>

        <div class=" col-12 col-lg-12">
          <div class="app-card app-card-stat shadow-sm h-100">
            <br />
            <div class="app-card-body p-3 p-lg-4">
              <div class="clearfix">
                <h1 class="app-page-title" style="float:left"> Current Projects</h1>
                <a href="manage-projects.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fas fa-list"></i> View All</a>
              </div>
              <hr class="mb-4">
              <form class="settings-form">
                <div class="mb-3">
                  <table id="myTable" class="table app-table-hover mb-0 text-left">
                    <thead>
                      <tr>
                        <th class="cell">Project Name</th>
                        <th class="cell">Location</th>
                        <th class="cell">Due Date</th>
                        <th class="cell">Status</th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = "SELECT * FROM projects WHERE status != 'Cancelled' AND status != 'Finished' ORDER BY projects.id DESC LIMIT 3 ";
                      $result = mysqli_query($conn, $sql);
                      $count = mysqli_num_rows($result);

                      //check if projects exist
                      if ($count > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {
                          $id = $row['id'];
                          $projectname = $row['name'];
                          $location = $row['location'];
                          $duedate = $row['end_date'];
                          $date = date("M d, Y", strtotime($duedate));
                          $status = $row['status'];
                      ?>
                          <tr>
                            <td class="cell" style="padding-top: 1em"><?php echo $projectname; ?></td>
                            <td class="cell" style="padding-top: 1em"><?php echo $location; ?></td>
                            <td class="cell" style="padding-top: 1em"><?php echo $date; ?></td>
                            <?php
                            //check if project is on hold
                            if ($row['status'] == "On Hold") {
                            ?>
                              <td style="padding-top: 1em; font-weight:bold; color:blue"><?php echo $status; ?></td>
                            <?php
                              //check if project is started
                            } else if ($row['status'] == "Started") {
                            ?>
                              <td style="padding-top: 1em; font-weight:bold; color:orange"><?php echo $status; ?></td>
                            <?php
                            }
                            ?>
                          </tr>
                      <?php
                        }
                      } else {
                        // echo "<script>alert('No Projects Found!')</script>";
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

        <div class=" col-12 col-lg-12">
          <div class="app-card app-card-stat shadow-sm h-100">
            <br />
            <div class="app-card-body p-3 p-lg-4">
              <div class="clearfix">
                <h1 class="app-page-title" style="float:left">Current Orders</h1>
                <a href="manage-purchase-orders.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fas fa-list"></i> View All</a>
              </div>
              <hr class="mb-4">
              <form class="settings-form">
                <div class="mb-3">
                  <table id="2ndTable" class="table app-table-hover mb-0 text-left">
                    <thead>
                      <tr>
                        <th class="cell">Order ID</th>
                        <th class="cell">Name</th>
                        <th class="cell">Price</th>
                        <th class="cell">Quantity</th>
                        <th class="cell">Amount</th>
                        <th class="cell">Date Ordered</th>
                        <th class="cell">Status</th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = "SELECT * FROM materials, orders WHERE orders.product_id = materials.id AND orders.status != 'Received' AND orders.status != 'Returning' AND orders.status != 'Returned' ORDER BY orders.id DESC LIMIT 3";
                      $result = mysqli_query($conn, $sql);
                      $count = mysqli_num_rows($result);

                      //check if projects exist
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
                          $status = $row['status'];
                          $date = date("M d, Y", strtotime($date_ordered));
                      ?>
                          <tr>
                            <td class="cell" style="padding-top: 1em"><?php echo $order_id; ?></td>
                            <td class="cell" style="padding-top: 1em">
                              <p>Name: <b><?php echo $products; ?></b></p>
                              <p><small>Description: <b><?php echo $description; ?></b></small></p>
                              <p><small>Supplier: <b><?php echo $supplier; ?></b></small></p>
                            </td>
                            <td class="cell" style="padding-top: 1em">₱<?php echo number_format($product_price, 2, '.', ','); ?></td>
                            <td class="cell" style="padding-top: 1em"><?php echo $qty . " " . $unit; ?></td>
                            <td class="cell" style="padding-top: 1em">₱<?php echo number_format($amount_paid, 2, '.', ','); ?></td>
                            <td class="cell" style="padding-top: 1em"><?php echo $date; ?></td>
                            <?php
                            //check if status is pending
                            if ($status == "Pending") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:blue">Order Pending</td>
                            <?php
                              //check if status is approved
                            } else if ($status == "Approved") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:gold ">Order Approved</td>
                            <?php
                              //check if status is rejected
                            } else if ($status == "Rejected") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:red">Order Rejected</td>
                            <?php
                              //check if status is on delivery
                            } else if ($status == "On Delivery") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:orange">On Delivery</td>
                            <?php
                              //check if status is received
                            } else if ($status == "Received") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:green">Received</td>
                            <?php
                              //check if status is returning
                            } else if ($status == "Returning") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:red">Returning</td>
                            <?php
                              //check if status is returned
                            } else if ($status == "Returned") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:green">Returned</td>
                            <?php
                            }
                            ?>
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

        <!--//col-->
        <div class="col-12 col-lg-6">
          <div class="app-card app-card-stat shadow-sm h-100">
            <br />
            <div class="app-card-body p-3 p-lg-4">
              <div class="clearfix">
                <h1 class="app-page-title" style="float:left">Current Receivables</h1>
                <a href="manage-receivables.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fas fa-list"></i> View All</a>
              </div>
              <hr class="mb-4">
              <form class="settings-form">
                <div class="mb-3">
                  <table id="3rdTable" class="table app-table-hover mb-0 text-left">
                    <thead>
                      <tr>
                        <th class="cell">Client</th>
                        <th class="cell">Total</th>
                        <th class="cell">Date Created</th>
                        <th class="cell">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = "SELECT * FROM receivables WHERE total_invoice != '' LIMIT 3";
                      $result = mysqli_query($conn, $sql);
                      $count = mysqli_num_rows($result);

                      //check if projects exist
                      if ($count > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {
                          $client_name = $row['client_name'];
                          $receivable_date_added = $row['receivable_date_added'];
                          $total_invoice = $row['total_invoice'];
                          $receivable_status = $row['receivable_status'];
                      ?>
                          <tr>
                            <td class="cell" style="padding-top:0.5em"><?php echo $client_name; ?></td>
                            <td class="cell" style="padding-top:0.5em">₱<?php echo number_format($total_invoice, 2, '.', ','); ?></td>
                            <td class="cell" style="padding-top:0.5em"><?php echo date("M d, Y - h:i a", strtotime($receivable_date_added)); ?></td>
                            <?php
                            //check if receivable status is pending
                            if ($row['receivable_status'] == "Unpaid") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:orange">Unpaid</td>
                            <?php
                              //check if receivable status is received
                            } else if ($row['receivable_status'] == "Partial") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:green">Partially Paid</td>
                            <?php
                            } else if ($row['receivable_status'] == "Paid") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:green">Fully Paid</td>
                            <?php
                            }
                            ?>
                          </tr>
                      <?php
                        }
                      } else {
                        // echo "<script>alert('No Receivables Found!')</script>";
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
        <!--//col-->
        <div class="col-12 col-lg-6">
          <div class="app-card app-card-stat shadow-sm h-100">
            <br />
            <div class="app-card-body p-3 p-lg-4">
              <div class="clearfix">
                <h1 class="app-page-title" style="float:left">Current Payables</h1>
                <a href="manage-payables.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fas fa-list"></i> View All</a>
              </div>
              <hr class="mb-4">
              <form class="settings-form">
                <div class="mb-3">
                  <table id="4thTable" class="table app-table-hover mb-0 text-left">
                    <thead>
                      <tr>
                        <th class="cell">Supplier</th>
                        <th class="cell" style="width:25%">Total</th>
                        <th class="cell" style="width:30%">Date Created</th>
                        <th class="cell">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $sql = "SELECT * FROM materials, payables WHERE payables.product_id = materials.id";
                      $result = mysqli_query($conn, $sql);
                      $count = mysqli_num_rows($result);

                      //check if recievables exist
                      if ($count > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {
                          $supplier = $row['supplier'];
                          $amount_paid = $row['amount_paid'];
                          $date_received = $row['date_received'];
                          $date = date("M d, Y", strtotime($date_received));
                          $status = $row['status'];
                      ?>
                          <tr>
                            <td class="cell" style="padding-top: 1em"><?php echo $supplier; ?></td>
                            <td class="cell" style="padding-top: 1em">₱<?php echo number_format($amount_paid, 2, '.', ','); ?></td>
                            <td class="cell" style="padding-top: 1em"><?php echo $date; ?></td>
                            <?php
                            //check if payable status is unpaid
                            if ($row['status'] == "Unpaid") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:red">Unpaid</td>
                            <?php
                              //check if payable status is partially paid
                            } else if ($row['status'] == "Partial") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:blue">Partially Paid</td>
                            <?php
                              //check if payable status is paid
                            } else if ($row['status'] == "Paid") {
                            ?>
                              <td class="cell" style="padding-top: 1em; font-weight:bold; color:green">Fully Paid</td>
                            <?php
                            }
                            ?>
                          </tr>
                      <?php
                        }
                      } else {
                        // echo "<script>alert('No Payables Found!')</script>";
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
        <!--//col-->
      </div>
      <!--//row-->
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

      $('#2ndTable').DataTable({
        "aaSorting": [],
        "sScrollX": "100%",
        "sScrollXInner": "100%",
        "bScrollCollapse": true
      });

      $('#3rdTable').DataTable({
        "aaSorting": [],
        "sScrollX": "100%",
        "sScrollXInner": "100%",
        "bScrollCollapse": true
      });

      $('#4thTable').DataTable({
        "aaSorting": [],
        "sScrollX": "100%",
        "sScrollXInner": "100%",
        "bScrollCollapse": true
      });

    });
  </script>

  <?php

  $sql = "SELECT sum(price) as income, monthname(payment_date) as month FROM payments WHERE month(payment_date) AND year(payment_date) = YEAR(CURRENT_DATE) GROUP BY month(payment_date)";
  $result = mysqli_query($conn, $sql);

  foreach ($result as $data) {

    $month[] = $data['month'];
    $income[] = $data['income'];
  }


  ?>

  <script>
    const labels = <?php echo json_encode($month); ?>;
    const data = {
      labels: labels,
      datasets: [{
        label: 'Income Per Month',
        data: <?php echo json_encode($income); ?>,
        backgroundColor: '#B3D1F9',
        borderColor: '#B3D1F9',
        borderWidth: 1
      }]
    };

    const config = {
      type: 'bar',
      data: data,
      options: {
        scales: {
          y: {
            beginAtZero: true,
            grace: '5%',
          }
        },
        responsive: true
      },
    };

    Chart.defaults.font.size = 14;
    var myChart = new Chart(
      document.getElementById('myChart'),
      config
    );
  </script>

  <!-- Page Specific JS -->
  <script src="assets/js/app.js"></script>
</body>

</html>