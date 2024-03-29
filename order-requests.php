<?php

include('connections/config.php');

if (!isset($_GET['key']) && !isset($_GET['token'])) {

    header("Location: error-page.php");
} else {

    $email = $_GET['key'];
    $token = $_GET['token'];

    $sql = "SELECT * FROM materials, orders WHERE orders.product_id = materials.id AND orders.email_address = '$email' AND orders.token = '$token' AND orders.status = 'Pending'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($result == TRUE) {

        $count = mysqli_num_rows($result);

        if ($count == 1) {
            $id = $row['id'];
            $order_id = $row['order_id'];
            $products = $row['products'];
            $unit = $row['unit'];
            $price = $row['price'];
            $qty = $row['qty'];
            $amount_paid = $row['amount_paid'];
        } else {
            header("Location: error-page.php");
        }
    }
}

if (isset($_POST['approve'])) {

    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = "On Delivery";
    $token = 0;
    $notification_status = 0;

    $sql = "UPDATE orders SET status = '$status', token = '$token', date_approved= now(), notification_status = '$notification_status' WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result == TRUE) {
        header('Location: thank-you-page.php');
    } else {
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/login.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link id="theme-style" rel="stylesheet" href="assets/dataTables/jquery.dataTables.min.css">
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <link rel="stylesheet" href="assets/css/style.css" />

    <!-- JQuery -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <!-- used to prevent data to insert again when page is refreshed-->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <title>CONTRIVE | Order Request</title>
</head>

<body>
    <!-- Login -->
    <div class="container">
        <div class="card col-lg-12 col-md-12 col-12 mx-auto px-3 py-4">
            <div class="row justify-content-center">
                <center class="mb-5">
                    <img src="assets/images/kcs.png" style="width:20em">
                </center>
            </div>
            <h4 class="text-center mb-4">Order Details</h4>
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $order_id; ?></td>
                        <td><?php echo $products; ?></td>
                        <td>₱<?php echo $price; ?></td>
                        <td><?php echo $qty . " " . $unit; ?></td>
                        <td>₱<?php echo $amount_paid; ?></td>
                    </tr>
                </tbody>
            </table>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="pt-5">
                    <button type="submit" name="approve" class="button-green">Approve Order</button>
                    <button onclick="location.href='decline-order-request.php?key=<?php echo $email; ?>&token=<?php echo $token; ?>'" type="button" class="button-red">Reject Order</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Javascript -->
    <script src=" assets/plugins/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page Specific JS -->
    <script src="assets/js/app.js"></script>

    <!-- Datatables -->
    <script src="assets/dataTables/jquery-3.5.1.js"></script>
    <script src="assets/dataTables/jquery.dataTables.min.js"></script>
</body>


</html>