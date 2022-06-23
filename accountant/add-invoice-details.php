<?php

include('../connections/config.php');

//check if the user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

if (isset($_POST['add'])) {

    if (empty($_POST['transportation']) or empty($_POST['consultation_fee'])) {

        $_SESSION['required'] = "All Fields Are Required";
    } else {

        $transportation = mysqli_real_escape_string($conn, $_POST['transportation']);
        $consultation_fee = mysqli_real_escape_string($conn, $_POST['consultation_fee']);

        $total_miscellaneous = $transportation + $consultation_fee;

        $sql = "UPDATE invoices SET transportation = '$transportation', consultation_fee = '$consultation_fee', total_miscellaneous = '$total_miscellaneous' WHERE invoice_id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result == TRUE) {

            $_SESSION['add-charges'] = "Miscellaneous Charges Added Successfully!";
        } else {

            $_SESSION['failed-to-add'] = "Failed To Add Miscellaneous Charges.";
        }
    }
}

if (isset($_POST['create'])) {

    if (empty($_POST['status']) or empty($_POST['date_created']) or empty($_POST['due_date']) or empty($_POST['transportation']) or empty($_POST['consultation_fee'])) {

        $_SESSION['required'] = "All Fields Are Required.";
    } else {

        $client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
        $transportation = mysqli_real_escape_string($conn, $_POST['transportation']);
        $consultation_fee = mysqli_real_escape_string($conn, $_POST['consultation_fee']);

        $total_miscellaneous = $transportation + $consultation_fee;

        $total_materials = mysqli_real_escape_string($conn, $_POST['total_materials']);
        $total_labor = mysqli_real_escape_string($conn, $_POST['total_labor']);
        $sub_total = mysqli_real_escape_string($conn, $_POST['sub_total']);
        $total_tax = mysqli_real_escape_string($conn, $_POST['total_tax']);
        $total_invoice = mysqli_real_escape_string($conn, $_POST['total_invoice']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $date_created = mysqli_real_escape_string($conn, $_POST['date_created']);
        $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
        $receivable_status = "Unpaid";

        if ($total_invoice == 0) {

            $_SESSION['no-invoice-amount'] = "Invoice Details Not Complete.";
        } else {

            $sql = "UPDATE invoices SET client = '$client_name', transportation = '$transportation', consultation_fee = '$consultation_fee', total_miscellaneous = '$total_miscellaneous', total_materials = '$total_materials', total_labor = '$total_labor' , subtotal = '$sub_total', total_tax = '$total_tax', total_invoice = '$total_invoice' , status = '$status' , date_created = '$date_created' , due_date = '$due_date', date_added = now() WHERE invoice_id = $id";
            $result = mysqli_query($conn, $sql);

            if ($result == TRUE) {

                $sql = "INSERT INTO receivables (invoice_id, client_name, total_invoice,total_remaining, receivable_status) VALUES ('$id', '$client_name', '$total_invoice', '$total_invoice', '$receivable_status')";
                $result = mysqli_query($conn, $sql);

                //update last activity date and time
                if ($result = TRUE) {
                } else {
                    echo "<script>alert('Error in Updating Last Activity')</script>";
                }

                $_SESSION['create-invoice'] = "Invoice Created Successfully!";
                header("Location: invoice.php");
            } else {

                $_SESSION['failed-to-create'] = "Failed To Create Invoice.";
            }
        }
    }
}


$id = $_GET['ID'];

$sql = "SELECT * FROM staffs, clients, projects, invoices WHERE staffs.id = projects.engineer_id AND clients.name = projects.client_name AND invoices.project_id = projects.id AND invoices.invoice_id = '$id' ORDER by invoice_id DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are project records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if project exist
    if ($count == 1) {

        $invoice_id = $row['invoice_id'];
        $name = $row['name'];
        $project_description = $row['project_description'];
        $full_name = $row['full_name'];
        $location = $row['location'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
        $client_name = $row['client_name'];
        $address = $row['address'];
        $contact = $row['contact'];
        $email = $row['email'];
        $transportation = $row['transportation'];
        $consultation_fee = $row['consultation_fee'];
        $total_miscellaneous = $row['total_miscellaneous'];
    } else {

        $_SESSION['invoice-not-found'] = "Invoice Not Found.";
        header("Location: invoice.php");
    }
}

$id = $_GET['ID'];

$sql = "SELECT * , SUM(requirements.price * requirements.quantity) as total_amount FROM materials, requirements WHERE materials.id = requirements.material_id AND project_id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are project records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if project exist
    if ($count == 1) {

        $total_materials = $row['total_amount'];
    } else {
    }
}

$id = $_GET['ID'];

$sql = "SELECT * , SUM(workers.rate * workers.hours_per_day) as total_amount FROM workers, teams WHERE workers.position_id = teams.position_id AND workers.id = teams.member_id AND project_id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are project records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if project exist
    if ($count == 1) {

        $total_labor = $row['total_amount'];
    } else {
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['invoice-not-found'] = "Invoice Not Found.";
    header("Location: invoice.php");
}

$sub_total = $total_materials + $total_labor + $total_miscellaneous;
$tax_rate = "0.12";
$total_tax = $sub_total * $tax_rate;
$total_invoice_amount = $sub_total + $total_tax;

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
                    <li class="breadcrumb-item active">Add Invoice Details</li>
                </ol>
                <h1 class="app-page-title">Invoice Details</h1>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['required'])) {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['required']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['required']);
                }
                if (isset($_SESSION['failed-to-create'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-create']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['failed-to-create']);
                }
                if (isset($_SESSION['add-charges'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['add-charges']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['add-charges']);
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
                if (isset($_SESSION['no-invoice-amount'])) {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['no-invoice-amount']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['no-invoice-amount']);
                }
                ?>
                <form id="create-invoice" method="post">
                    <div class="row g-4 settings-section">
                        <div class="col-12 col-md-12">
                            <div class="row g-2">
                                <div class="col-md-3 col-sm-6">
                                    <p>Invoice No. <input type="text" class="form-control text-end" style="font-weight:bold" value="#<?php echo $invoice_id; ?>" readonly></p>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <p>Invoice Status <small style="font-weight:bold; font-size:1em; color:red">*</small>
                                        <select name="status" class="form-select">
                                            <option selected disabled>-- Mark Invoice As --</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Partially Paid">Partially Paid</option>
                                            <option value="Fully Paid">Fully Paid</option>
                                        </select>
                                    </p>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <p>Date of Invoice: <small style="font-weight:bold; font-size:1em; color:red">*</small>
                                        <input type="date" class="form-control" name="date_created" id="date" placeholder="Date">
                                    </p>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <p>Due Date: <small style="font-weight:bold; font-size:1em; color:red">*</small>
                                        <input type="date" class="form-control" name="due_date" id="date" placeholder="Date">
                                    </p>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table class="table">
                                            <tbody>
                                                <p style="font-size: 1.5em; font-weight:bold; color:#000">Project and Client Information</p>
                                                <hr>
                                                <tr>
                                                    <td class="cell py-3"><b>Name: </b> <?php echo $name; ?>
                                                    </td>
                                                    <td class="cell py-3"><b>Description: </b> <?php echo $project_description; ?>
                                                    </td>
                                                    <td class="cell py-3"><b>Engineer: </b> <?php echo $full_name; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="cell py-3"><b>Location: </b> <?php echo $location; ?>
                                                    </td>
                                                    <td class="cell py-3"><b>Start Date: </b> <?php echo date("M d, Y", strtotime($start_date)); ?>
                                                    </td>
                                                    <td class="cell py-3"><b>End Date: </b> <?php echo date("M d, Y", strtotime($end_date)); ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="cell py-3"><b>Client Name: </b> <?php echo $client_name; ?>
                                                    </td>
                                                    <input type="hidden" name="client_name" value="<?php echo $client_name; ?>">
                                                    <td class="cell py-3"><b>Address: </b> <?php echo $address; ?>
                                                    </td>
                                                    <td class="cell py-3"><b>Contact: </b> <?php echo $contact; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="cell py-3"><b>Email: </b> <?php echo $email; ?>
                                                    </td>
                                                    <td class="cell py-3">
                                                    </td>
                                                    <td class="cell py-3">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>
                        </div>

                        <div class="row g-2 mt-2">
                            <div class="col-sm-12 col-md-6">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <table id="myTable" class="table app-table-hover mb-0 text-left">
                                        <thead>
                                            <div class="clearfix">
                                                <h1 class="app-page-title" style="float:left">Materials</h1>
                                                <a href="project-requirements.php?ID=<?php echo $id; ?> " target="blank" class="btn app-btn btn-success" style="color:white; float:right"><i class="fas fa-plus" style="color:white"></i></a>
                                            </div>
                                            <hr>
                                            <tr>
                                                <th class="cell" style="text-align:center">Material</th>
                                                <th class="cell" style="text-align:center">Price</th>
                                                <th class="cell" style="text-align:center">Quantity</th>
                                                <th class="cell" style="text-align:center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $id = $_GET['ID'];

                                            $sql = "SELECT * FROM materials, requirements WHERE materials.id = requirements.material_id AND project_id = $id";
                                            $result = mysqli_query($conn, $sql);
                                            $count = mysqli_num_rows($result);

                                            //check if clients exist
                                            if ($count > 0) {

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $name = $row['name'];
                                                    $price = $row['price'];
                                                    $quantity = $row['quantity'];
                                                    $unit = $row['unit'];
                                                    $total = $price * $quantity;
                                            ?>
                                                    <tr>
                                                        <td class="cell"><?php echo $name; ?></td>
                                                        <td class="cell" style="text-align:right">₱<?php echo number_format($price, 2, '.', ','); ?></td>
                                                        <td class="cell" style="text-align:center"><?php echo $quantity . " " . $unit; ?></td>
                                                        <td class="cell" style="text-align:right">₱<?php echo number_format($total, 2, '.', ','); ?></td>
                                                    </tr>
                                                <?php
                                                }
                                            } else { ?>
                                                <tr class="text-center">
                                                    <td class="pt-4" colspan="4">
                                                        <p style="font-weight:bold; font-size: 1.25em; color:red">No Materials Added Yet!</p>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <table id="myTable1" class="table app-table-hover mb-0 text-left">
                                        <thead>
                                            <div class="clearfix">
                                                <h1 class="app-page-title" style="float:left">Labor</h1>
                                                <a href="project-workers.php?ID=<?php echo $id; ?> " target="blank" class="btn app-btn btn-success" style="color:white; float:right"><i class="fas fa-plus" style="color:white"></i></a>
                                            </div>
                                            <hr>
                                            <tr>
                                                <th class="cell" style="text-align:center">Position</th>
                                                <th class="cell" style="text-align:center">Hourly Rate</th>
                                                <th class="cell" style="text-align:center">Hours (Per Shift)</th>
                                                <th class="cell" style="text-align:center">Daily Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $id = $_GET['ID'];

                                            $sql = "SELECT * FROM workers, teams WHERE workers.position_id = teams.position_id AND workers.id = teams.member_id AND project_id = $id";
                                            $result = mysqli_query($conn, $sql);
                                            $count = mysqli_num_rows($result);

                                            //check if worker record are existing in db
                                            if ($count > 0) {

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $position_name = $row['position'];
                                                    $hours_per_day = $row['hours_per_day'];
                                                    $rate = $row['rate'];
                                                    $total_rate = $rate * $hours_per_day;
                                            ?>
                                                    <tr>
                                                        <td class="cell"><?php echo $position_name; ?></td>
                                                        <td class="cell" style="text-align:right">₱<?php echo number_format($rate, 2, '.', ','); ?></td>
                                                        <td class="cell" style="text-align:center"><?php echo $hours_per_day; ?> hrs</td>
                                                        <td class="cell" style="text-align:right">₱<?php echo number_format($total_rate, 2, '.', ','); ?></td>
                                                    </tr>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <tr class="text-center">
                                                    <td class="pt-4" colspan="4">
                                                        <p style="font-weight:bold; font-size: 1.25em; color:red">No Labors Added Yet!</p>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <form method="post">
                                <!-- <form id="add-charges" method="post""> -->
                                <div class=" col-sm-12 col-md-6">
                                    <div class="app-card app-card-settings shadow-sm p-4">
                                        <p style="font-size: 1.5em; font-weight:bold; color:#000">Miscellaneous Charges</p>
                                        <hr>
                                        <div class="mb-3">
                                            <label for="setting-input-3" class="form-label" style="color:#000">Transportation of Materials: <small style="font-weight:bold; font-size:1em; color:red">*</small></label>
                                            <input type="number" name="transportation" class="form-control" id="setting-input-3" value="<?php echo $transportation; ?>" autocomplete="off" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="setting-input-3" class="form-label" style="color:#000">Consultations with Architect: <small style="font-weight:bold; font-size:1em; color:red">*</small></label>
                                            <input type="number" name="consultation_fee" class="form-control" row="setting-input-3" value="<?php echo $consultation_fee; ?>" autocomplete="off" required>
                                        </div>
                                        <div>
                                            <button type="submit" name="add" class="btn btn-success" style="color:white">Add Charges</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- </form> -->
                            <div class="col-sm-12 col-md-6">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label" style="color:#000">Total Materials Amount: </label>
                                        <input type="text" class="form-control" id="setting-input-3" style="text-align:right; font-weight:bold" placeholder="₱0.00" value="₱<?php echo number_format($total_materials, 2, '.', ','); ?>" readonly required>
                                        <input type="hidden" name="total_materials" value="<?php echo $total_materials; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label" style="color:#000">Total Labor Amount: </label>
                                        <input type="text" class="form-control" id="setting-input-3" style="text-align:right; font-weight:bold" placeholder="₱0.00" value="₱<?php echo number_format($total_labor, 2, '.', ','); ?>" autocomplete="off" readonly required>
                                        <input type="hidden" name="total_labor" value="<?php echo $total_labor; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label" style="color:#000">Total Miscellaneous Fee: </label>
                                        <input type="text" class="form-control" id="setting-input-3" style="text-align:right; font-weight:bold" placeholder="₱0.00" value="₱<?php echo number_format($total_miscellaneous, 2, '.', ','); ?>" autocomplete="off" readonly required>
                                        <input type="hidden" name="total_miscellaneous" value="<?php echo $total_miscellaneous; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label" style="color:#000">Subtotal: </label>
                                        <input type="text" class="form-control" id="setting-input-3" style="text-align:right; font-weight:bold" placeholder="₱0.00" value="₱<?php echo number_format($sub_total, 2, '.', ','); ?>" autocomplete="off" readonly required>
                                        <input type="hidden" name="sub_total" value="<?php echo $sub_total; ?>">
                                    </div>
                                    <!-- <div class="mb-3 multiselect-drop">
                                        <input type="checkbox" value="0.12" name="tax" class="tax" id="tax" onchange="valueChanged()">
                                        <label for="setting-input-3" class="form-label" style="color:#000">Check This To Apply Tax</label>
                                    </div> -->
                                    <div class="mb-3 tax-div">
                                        <label for="setting-input-3" class="form-label" style="color:#000">Tax Rate: (%)</label>
                                        <input type="text" name="tax_rate" class="form-control" id="tax_rate" style="text-align:right; font-weight:bold" autocomplete="off" value="<?php echo $tax_rate; ?>" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label" style="color:#000">Total Tax: </label>
                                        <input type="text" class="form-control" id="setting-input-3" style="text-align:right; font-weight:bold" placeholder="₱0.00" autocomplete="off" value="₱<?php echo number_format($total_tax, 2, '.', ','); ?>" readonly required>
                                        <input type="hidden" name="total_tax" value="<?php echo $total_tax; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label" style="color:#000">Total Invoice Amount: </label>
                                        <input type="text" class="form-control" id="setting-input-3" style="text-align:right; font-weight:bold" placeholder="₱0.00" autocomplete="off" value="₱<?php echo number_format($total_invoice_amount, 2, '.', ','); ?>" readonly required>
                                        <input type="hidden" name="total_invoice" value="<?php echo $total_invoice_amount; ?>">
                                    </div>
                                    <div>
                                        <button type="submit" name="create" class="btn btn-success" style="color:white">Create Invoice</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <!--//row-->
                </form>


                <hr class="my-4">
            </div>
            <!--//container-fluid-->
        </div>
        <!--//app-content-->

    </div>

</body>

<!-- Javascript -->
<script src="assets/plugins/popper.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

<!-- Page Specific JS -->
<script src="assets/js/app.js"></script>

<!-- Datatables -->
<script src="dataTables/jquery-3.5.1.js"></script>
<script src="dataTables/jquery.dataTables.min.js"></script>

<!-- <script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "aaSorting": [],
            "sScrollX": "100%",
            "sScrollXInner": "100%",
            "bScrollCollapse": true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#myTable1').DataTable({
            "aaSorting": [],
            "sScrollX": "100%",
            "sScrollXInner": "100%",
            "bScrollCollapse": true
        });
    });
</script>

<script>
    function valueChanged() {
        if ($(".tax").is(":checked"))
            $(".tax-div").show();
        else
            $(".tax-div").hide();
    }
</script> -->

</body>

</html>