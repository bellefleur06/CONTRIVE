<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
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
        $date_created = $row['date_created'];
        $due_date = $row['due_date'];
        $status = $row['status'];
        $client_name = $row['client_name'];
        $address = $row['address'];
        $contact = $row['contact'];
        $email = $row['email'];
        $transportation = $row['transportation'];
        $consultation_fee = $row['consultation_fee'];
        $total_miscellaneous = $row['total_miscellaneous'];
        $total_invoice = $row['total_invoice'];
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTRIVE | Print Invoice</title>

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <link id="theme-style" rel="stylesheet" href="assets/css/style.css">

    <style type="text/css" media="print">
        @media print {

            .noprint,
            .noprint * {
                display: none !important;

            }
        }
    </style>

</head>

<body>

    <div class="container">
        <br>
        <center>
            <img src="../assets/images/kcs.png" style="width:20em">
        </center>
        <br>
        <table id="ready" class="table table-bordered" style="width:100%">
            <tbody>
                <tr>
                    <th style="text-align:center" colspan="3">Invoice Details</th>
                </tr>
                <tr>
                    <td class="cell"><b>Invoice ID: </b> #<?php echo $invoice_id; ?>
                    </td>
                    <td class="cell"><b>Invoice Amount: </b> ₱<?php echo number_format($total_invoice, 2, '.', ','); ?>
                    </td>
                    <td class="cell"><b>Project: </b> <?php echo $name; ?>
                    </td>
                </tr>
                <tr>
                    <td class="cell"><b>Client Name: </b> <?php echo $client_name; ?>
                    </td>
                    <td class="cell"><b>Date Issued: </b> <?php echo date("M d, Y", strtotime($date_created)); ?>
                    </td>
                    <td class="cell"><b>Due Date: </b> <?php echo date("M d, Y", strtotime($due_date)); ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table id="ready" class="table table-bordered text-center" style="width:100%">
            <thead>
                <tr>
                    <th colspan="4">Materials</th>
                </tr>
                <tr>
                    <th style="text-align:left">Material</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th style="text-align:right">Total</th>
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
                            <td style="text-align:left"><?php echo $name; ?></td>
                            <td>₱<?php echo number_format($price, 2, '.', ','); ?></td>
                            <td class=" cell"><?php echo $quantity . " " . $unit; ?></td>
                            <td style="text-align:right">₱<?php echo number_format($total, 2, '.', ','); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th style="text-align:left">Total Materials Amount</th>
                        <th style="text-align:right" colspan="3">₱<?php echo number_format($total_materials, 2, '.', ','); ?></th>
                    </tr>
                <?php
                } else {
                ?>
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
        <br>
        <table id="ready" class="table table-bordered text-center">
            <thead>
                <tr>
                    <th colspan="4">Labors</th>
                </tr>
                <tr>
                    <th class="cell" style="text-align:left">Position</th>
                    <th class="cell">Hourly Rate</th>
                    <th class="cell">Hours (Per Shift)</th>
                    <th class="cell" style="text-align:right">Daily Rate</th>
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
                            <td class="cell" style="text-align:left"><?php echo $position_name; ?></td>
                            <td class="cell">₱<?php echo number_format($rate, 2, '.', ','); ?></td>
                            <td class="cell"><?php echo $hours_per_day; ?> hrs</td>
                            <td class="cell" style="text-align:right">₱<?php echo number_format($total_rate, 2, '.', ','); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th class="cell" style="text-align:left">Total Labor Amount</th>
                        <th class="cell" style="text-align:right" colspan="3">₱<?php echo number_format($total_labor, 2, '.', ','); ?></th>
                    </tr>
                <?php
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
        <br>
        <table id="ready" class="table table-bordered" style="width:100%">
            <tbody>
                <tr>
                    <th style="text-align:center" colspan="2">Miscellaneous Charges</th>
                </tr>
                <tr>
                    <td class="cell">Transportation of Materials
                    </td>
                    <td class="cell" style="text-align:right">₱<?php echo number_format($transportation, 2, '.', ','); ?>
                    </td>

                </tr>
                <tr>
                    <td class="cell">Consultation with Architech
                    </td>
                    <td class="cell" style="text-align:right">₱<?php echo number_format($consultation_fee, 2, '.', ','); ?>
                    </td>
                </tr>
                <tr>
                    <td class="cell"><b>Total Miscellaneous Fee</b>
                    </td>
                    <td class="cell" style="text-align:right"><b>₱<?php echo number_format($total_miscellaneous, 2, '.', ','); ?></b>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table id="ready" class="table table-bordered" style="width:100%">
            <tbody>
                <tr>
                    <th style="text-align:center" colspan="2">Total Invoice Amount Breakdown</th>
                </tr>
                <tr>
                    <td class="cell">Total Materials Amount
                    </td>
                    <td class="cell" style="text-align:right">₱<?php echo number_format($transportation, 2, '.', ','); ?>
                    </td>
                </tr>
                <tr>
                    <td class="cell">Total Labor Amount
                    </td>
                    <td class="cell" style="text-align:right">₱<?php echo number_format($consultation_fee, 2, '.', ','); ?>
                    </td>
                </tr>
                <tr>
                    <td class="cell">Total Miscellaneous Fee
                    </td>
                    <td class="cell" style="text-align:right">₱<?php echo number_format($total_miscellaneous, 2, '.', ','); ?>
                    </td>
                </tr>
                <tr>
                    <td class="cell">Subtotal
                    </td>
                    <td class="cell" style="text-align:right">₱<?php echo number_format($sub_total, 2, '.', ','); ?>
                    </td>

                </tr>
                <tr>
                    <td class="cell">Tax Rate (%)
                    </td>
                    <td class="cell" style="text-align:right"> 0.12%
                    </td>
                </tr>
                <tr>
                    <td class="cell">Total Tax
                    </td>
                    <td class="cell" style="text-align:right">₱<?php echo number_format($total_tax, 2, '.', ','); ?>
                    </td>
                </tr>
                <tr>
                    <td class="cell"><b>Total Invoice Amount</b>
                    </td>
                    <td class="cell" style="text-align:right"><b>₱<?php echo number_format($total_invoice, 2, '.', ','); ?></b>
                    </td>
                </tr>
            </tbody>
        </table>
        <button class="btn btn-info noprint mb-3" style="color:white; float:right" onclick="print()"><i class="fa fa-print"></i> Click here to Print</button>
    </div>
</body>

</html>