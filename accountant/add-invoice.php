<?php

include('../connections/config.php');

//check if the user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

if (isset($_POST['create'])) {

    $project_id = mysqli_real_escape_string($conn, $_POST['project_id']);

    $sql = "INSERT INTO invoices (project_id) VALUES ('$project_id')";
    $result = mysqli_query($conn, $sql);

    if ($result) {

        $sql = "SELECT * FROM invoices WHERE project_id = '$project_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        $invoice_id = $row['invoice_id'];

        header("Location: add-invoice-details.php?ID=$invoice_id");
    }
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
    <link id="theme-style" rel="stylesheet" href="../assets/css/portal.css">
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="shortcut icon" href="../assets/images/icon.ico">

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
    include('accountant-navbar.php'); ?>

    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="invoice.php">Billing</a></li>
                    <li class="breadcrumb-item active">Add New Invoice</li>
                </ol>
                <h1 class="app-page-title"><span class="nav-icon"></span> Add New Invoice</h1>
                <hr class="mb-4">

                <form class="settings-form" method="post">
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-lg-6">
                            <div class="app-card app-card-stats-table h-100 shadow-sm">
                                <div class="app-card-header p-3">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto">
                                            <h4 class="app-card-title">Project Summary</h4>
                                        </div>
                                    </div>
                                    <!--//row-->
                                </div>
                                <!--//app-card-header-->
                                <div class="card-body p-3 p-lg-4">
                                    <div class="row g-2 justify-content-start align-items-center">
                                        <div class="mb-3">
                                            <fieldset class="form-group">
                                                <label for="setting-input-3" class="form-label">Project Name: </label>
                                                <select id="project" name="name" class="form-select" onchange='fetch_select(this.value)' required>
                                                    <option disabled selected="selected">-- Choose Project -- </option>
                                                    <?php
                                                    $sql = "SELECT * FROM projects WHERE status != 'Cancelled' ORDER by name ASC";
                                                    $result = mysqli_query($conn, $sql);
                                                    $count = mysqli_num_rows($result);

                                                    if ($count > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $id = $row['id'];
                                                            $name = $row['name'];
                                                    ?>
                                                            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                                        <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <option value="0">No Project Found</option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <input id="name" type="hidden" name="name" class="form-control" required readonly>
                                        <input id="id" type="hidden" name="project_id" class="form-control" required readonly>
                                        <div class="mb-3">
                                            <label for="setting-input-2" class="form-label">Desciption: </label>
                                            <input id="description" type="text" name="description" class="form-control" required readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="setting-input-2" class="form-label"> Engineer: </label>
                                            <input id="engineer_id" type="text" name="engineer_id" class="form-control" required readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="setting-input-2" class="form-label">Location: </label>
                                            <input id="location" type="text" name="location" class="form-control" required readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="setting-input-2" class="form-label"> Start Date: </label>
                                            <input id="start_date" type="text" name="start_date" class="form-control" required readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="setting-input-2" class="form-label">End Date: </label>
                                            <input id="end_date" type="text" name="end_date" class="form-control" required readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">

                            <div class="app-card app-card-stats-table h-100 shadow-sm ">
                                <div class="app-card-header p-3">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto">
                                            <h4 class="app-card-title">Client Information</h4>
                                        </div>
                                        <!--//col-->
                                    </div>
                                    <!--//row-->
                                </div>
                                <!--//app-card-header-->
                                <div class="card-body p-3 p-lg-4 align-items-center">
                                    <div class="row g-2 justify-content-start align-items-center">
                                        <div class="mb-3">
                                            <label for="setting-input-2" class="form-label">Client Name: </label>
                                            <input id="client_name" type="text" name="client_name" class="form-control" required readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="setting-input-2" class="form-label">Address: </label>
                                            <input id="address" type="text" name="address" class="form-control" required readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="setting-input-2" class="form-label">Contact No.: </label>
                                            <input id="contact" type="text" name="contact" class="form-control" required readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="setting-input-2" class="form-label">Email: </label>
                                            <input id="email" type="text" name="email" class="form-control" required readonly>
                                        </div>
                                        <div>
                                            <button type="submit" name="create" class="btn btn-success float-end" style="color:white">Create Project Invoice</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <hr class="my-4">
            </div>
        </div>
    </div>


    <!-- <div class="row g-2 justify-content-start align-items-center">
                                <div class="col-md-3 col-sm-6">
                                    <?php $invoice_no = "#" . rand(000000, 999999); ?>
                                    <p>Invoice No. <input type="text" class="form-control" required readonly name="text" value="<?php echo $invoice_no; ?>"></p>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <p>Mark As
                                        <select class="form-select">
                                            <option selected value="option-1">Paid</option>
                                            <option value="option-2">Partially Paid</option>
                                            <option value="option-3">Cancelled</option>
                                        </select>
                                    </p>
                                </div>
                `                <div class="col-md-3 col-sm-6">
                                    <p>Date of Invoice: <input type="date" class="form-control" required name="date" id="date" placeholder="Date"></p>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <p>Due Date: <input type="date" class="form-control" required name="date" id="date" placeholder="Date"></p>
                                </div>
                            </div> -->


    <!-- End of Client Info -->

    <!-- <div class="col-12 col-lg-6">
                                    <table class="table table-bordered table-hover table-striped" id="invoice_table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <p><a href="#" class="btn btn-success btn-xs add-row"><span class="fas fa-plus" style="color:white"></span></a> Material</p>
                                                </th>
                                                <th width="90">
                                                    <p>Qty</p>
                                                </th>
                                                <th width="100">
                                                    <p>Price</p>
                                                </th>
                                                <th width="100">
                                                    <p>Total</p>
                                                </th>
                                                <th>
                                                    <p>Action</p>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-right">
                                                    <div class="row g-2 justify-content-start align-items-center">
                                                        <div class="col-auto">
                                                            <input type="text" class="form-control form-group-sm item-input invoice_product" name="worker-position" placeholder="Enter Material">
                                                            <p class="item-select">or <a href="#">Select Material</a></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="row g-2 justify-content-start align-items-center">
                                                        <div class="col-auto">
                                                            <input type="number" class="form-control invoice_product_qty calculate" name="invoice_product_qty[]" value="1">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="input-group input-group-sm  no-margin-bottom">
                                                        <span class="input-group"></span>
                                                        <input type="number" class="form-control calculate invoice_product_price required" name="invoice_product_price[]" aria-describedby="sizing-addon1" placeholder="0.00">
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group"></span>
                                                        <input type="text" class="form-control calculate-sub" name="invoice_product_sub[]" id="invoice_product_sub" value="0.00" aria-describedby="sizing-addon1" disabled>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="input-group input-group-sm">
                                                        <a href="#" onclick="return confirm('Are you sure you want to delete this?')" class=" btn app-btn btn-danger" style="color:white"><i class="fa fa-trash"></i> Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div> -->
    <!-- <div class="col-12 col-lg-6">

                                    <table class="table table-bordered table-hover table-striped" id="invoice_table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <p><a href="#" class="btn btn-success btn-xs add-row"><span class="fas fa-plus" style="color:white"></span></a> Labor</p>
                                                </th>
                                                <th width="80">
                                                    <p>Hours</p>
                                                </th>
                                                <th>
                                                    <p>Rate</p>
                                                </th>
                                                <th width="100">
                                                    <p>Amount</p>
                                                </th>
                                                <th>
                                                    <p>Action</p>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="row g-2 justify-content-start align-items-center">
                                                        <div class="col-auto">
                                                            <!-- <a href="#" class="btn btn-danger btn-xs delete-row"><span class="fas fa-trash-alt" style="color:white"></span></a>
                                                            <input type="text" class="form-control form-group-sm item-input invoice_product" name="worker-position" placeholder="Enter Worker Position">
                                                            <p class="item-select">or <a href="#">Select Worker Position</a></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="row g-2 justify-content-start align-items-center">
                                                        <div class="col-auto">
                                                            <input type="number" class="form-control invoice_product_qty calculate" name="invoice_product_qty[]" value="1">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="input-group input-group-sm  no-margin-bottom">
                                                        <span class="input-group"></span>
                                                        <input type="number" class="form-control calculate invoice_product_price required" name="invoice_product_price[]" aria-describedby="sizing-addon1" placeholder="0.00">
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group"></span>
                                                        <input type="text" class="form-control calculate-sub" name="invoice_product_sub[]" id="invoice_product_sub" value="0.00" aria-describedby="sizing-addon1" disabled>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="input-group input-group-sm">
                                                        <a href="#" onclick="return confirm('Are you sure you want to delete this?')" class=" btn app-btn btn-danger" style="color:white"><i class="fa fa-trash"></i> Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> -->

    <!-- <div class="row g-4 mb-4">
                                    <div class="col-12 col-lg-6">
                                        <div class="app-card app-card-stats-table h-100 shadow-sm ">
                                            <div class="app-card-header p-3">
                                                <div class="row justify-content-between align-items-center">
                                                    <div class="col-auto">
                                                        <h4 class="app-card-title">Miscellaneous Charges</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-3 p-lg-4 align-items-center">
                                                <div class="row g-2 justify-content-start align-items-center">
                                                    <div class="form-group">
                                                        <p>Transportation of Materials</p>
                                                        <input type="number" name="price" class="form-control" id="amount" placeholder="₱ 0.00" autocomplete="off" required value="" />
                                                    </div>
                                                </div><br>
                                                <div class="form-group">
                                                    <p>Consultations with Architect</p>
                                                    <input type="number" name="price" class="form-control" id="amount" placeholder="₱ 0.00" autocomplete="off" required value="" />
                                                </div><br>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <div class="col-xs-6" style="float:right">
                                            <div class="row">
                                                <div class="col-xs-4 col-xs-offset-5">
                                                    <strong>Total Materials:</strong>
                                                </div>
                                                <div class="col-xs-3">
                                                    <span class="invoice-sub-total">0.00</span>
                                                    <input type="hidden" name="invoice_subtotal" id="invoice_subtotal">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4 col-xs-offset-5">
                                                    <strong>Total Labor:</strong>
                                                </div>
                                                <div class="col-xs-3">
                                                    <span class="invoice-discount">0.00</span>
                                                    <input type="hidden" name="invoice_discount" id="invoice_discount">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4 col-xs-offset-5">
                                                    <strong>Total Miscellaneous:</strong>
                                                </div>
                                                <div class="col-xs-3">
                                                    <span class="invoice-discount">0.00</span>
                                                    <input type="hidden" name="invoice_discount" id="invoice_discount">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4 col-xs-offset-5">
                                                    <strong>Subtotal:</strong>
                                                </div>
                                                <div class="col-xs-3">
                                                    <span class="invoice-discount">0.00</span>
                                                    <input type="hidden" name="invoice_discount" id="invoice_discount">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-4 col-xs-offset-5">
                                                    <strong class="">Tax Rate:</strong>
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group"></span>
                                                        <input type="text" class="form-control calculate" style="width: 15px;" name="invoice_tax" placeholder="0.00">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-4 col-xs-offset-5">
                                                    <strong>Total Tax:</strong>
                                                </div>
                                                <div class="col-xs-3">
                                                    <span class="invoice-discount">0.00</span>
                                                    <input type="hidden" name="invoice_discount" id="invoice_discount">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-4 col-xs-offset-5">
                                                    <strong>TOTAL:</strong>
                                                </div>
                                                <div class="col-xs-3">
                                                    <span class="invoice-total">0.00</span>
                                                    <input type="hidden" name="invoice_total" id="invoice_total">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div> -->
    <!--//app-card-body-->
    <!--//app-card-->

    <!--//row-->
    <!--//table-utilities-->

    <!--//col-auto-->



    <script>
        function fetch_select(val) {
            $.ajax({
                url: "fetch-project.php",
                type: "POST",
                data: {
                    "get_option": val
                },
                dataType: "JSON",
                success: function(data) {
                    $('#id').val((data[0].id));
                    $('#name').val((data[0].name));
                    $('#description').val((data[0].project_description));
                    $('#engineer_id').val((data[0].full_name));
                    $('#location').val((data[0].location));
                    $('#client_name').val((data[0].client_name));
                    $('#start_date').val((data[0].start_date));
                    $('#end_date').val((data[0].end_date));
                    $('#address').val((data[0].address));
                    $('#contact').val((data[0].contact));
                    $('#email').val((data[0].email));
                }

            });
        }
    </script>

    <!-- Javascript -->
    <script src="assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script>

</body>

</html>