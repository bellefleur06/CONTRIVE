<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM suppliers WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$supplier_id = $row['id'];
$supplier_name = $row['name'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTRIVE | Print Supplier Report</title>

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
            <thead>
                <tr>
                    <th style="text-align:center" colspan="3"> Supplier Report Details
                    </th>
                </tr>
                <tr>
                    <td>
                        <b>Name:</b> <?php echo $row['name']; ?>
                    </td>
                    <td>
                        <b>Category:</b> <?php echo $row['category_name']; ?>
                    </td>
                    <td>
                        <b>Address:</b> <?php echo $row['address']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Contact Person:</b> <?php echo $row['person']; ?>
                    </td>
                    <td>
                        <b>Contact No.:</b> <?php echo $row['contact']; ?>
                    </td>
                    <td>
                        <b>Email:</b> <?php echo $row['email']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>Date Added:</b> <?php echo $date = date("M d, Y", strtotime($row['date_added'])); ?>
                    </td>
                    <td>
                        <b>Status:</b> <?php echo $row['status']; ?>
                    </td>
                    <?php

                    $sql = "SELECT *, COUNT(*) as count FROM materials, suppliers, payables WHERE materials.id = payables.product_id AND materials.supplier = suppliers.name AND suppliers.name = '$supplier_name'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);

                    ?>
                    <td>
                        <b>No. of Purchase Transaction:</b> <?php echo $row['count']; ?>
                    </td>
                </tr>
        </table>
        <br>

        <table id="ready" class="table table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th colspan="5" style="text-align:center"> Supplier's Product Details
                    </th>
                </tr>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Purchase Price</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql = "SELECT * FROM suppliers, materials WHERE materials.supplier = '$supplier_name' AND suppliers.id = '$supplier_id'";
                $result = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($result);

                if ($count > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                        $name = $row['name'];
                        $category_name = $row['category_name'];
                        $description = $row['description'];
                        $unit = $row['unit'];
                ?>
                        <tr>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $category_name; ?></td>
                            <td><?php echo $description; ?></td>
                            <td><?php echo $unit; ?></td>
                            <td>₱<?php echo number_format($row['price'], 2, '.', ','); ?></td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <th class=" cell pt-4" colspan="5">
                            <h1 class="app-page-title" style="text-align:center; color:#d26d69; font-size:2rem;">No Products Found!</h1>
                        </th>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <button class="btn btn-info noprint mb-3" style="color:white; float:right" onclick="print()"><i class="fa fa-print"></i> Click here to Print</button>

</body>

</html>