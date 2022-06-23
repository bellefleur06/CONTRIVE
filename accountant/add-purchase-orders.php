<?php

include('../connections/config.php');

error_reporting(0);

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = 0;
$update = false;

//check if add button is clicked 
if (isset($_POST['submit'])) {

    $order_id = "#" . rand(00000000, 99999999);
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $material = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $amount = $price * $quantity;

    if (empty($material)) {

        $_SESSION['required'] = "All Fields Are Required.";
    } else {

        $sql =  "SELECT * FROM cart WHERE product_id = '$id'";
        $result = mysqli_query($conn, $sql);

        //check if item already exist in cart
        if (!$result->num_rows > 0) {

            $sql = "INSERT INTO cart (order_id, product_id, product_name, product_price, qty, total_price) VALUES ('$order_id','$id','$material','$price','$quantity','$amount')";
            $result = mysqli_query($conn, $sql);

            //check if insert result is true
            if ($result == TRUE) {

                $_SESSION['add-item'] = "Item Added Successfully!";

                //clear texboxes if the result is true
                $_POST['price'] = "";
            } else {

                $_SESSION['failed-to-add'] = "Failed to Add Item.";
            }
        } else {
            $_SESSION['item-already-exist'] = "Item Already Exist In The Cart.";
        }
    }
}

//remove an item in the cart
if (isset($_GET['delete'])) {

    $item_id = $_GET['delete'];

    $sql = "DELETE FROM cart WHERE id = '$item_id'";
    $result = mysqli_query($conn, $sql);

    //check if delete process is true
    if ($result == TRUE) {

        $_SESSION['remove-item'] = "Item Removed From Cart Successfully!";
        header("Location: add-purchase-orders.php");
    } else {

        $_SESSION['failed-to-remove'] = "Failed To Remove Item From Cart.";
    }
}

//remove all items in the cart
if (isset($_GET['remove'])) {

    $sql = "DELETE FROM cart";
    $result = mysqli_query($conn, $sql);

    //check if delete process is true
    if ($result == TRUE) {

        $_SESSION['remove-item'] = "All Items Removed From The Cart Successfully!";
        header("Location: add-purchase-orders.php");
    } else {

        $_SESSION['failed-to-remove'] = "Failed To Remove All Items From Cart.";
    }
}

//edit button
if (isset($_GET['ID'])) {

    $item_id = $_GET['ID'];
    $update = true;

    $sql = "SELECT * FROM materials, cart WHERE materials.id = cart.product_id AND cart.id = $item_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($result == TRUE) {

        $count = mysqli_num_rows($result);

        if ($count == 1) {
            $product = $row['product_name'];
            $description = $row['description'];
            $supplier = $row['supplier'];
            $unit = $row['unit'];
            $price = $row['product_price'];
            $quantity = $row['qty'];
        }
    }
}

//update item quantity
if (isset($_POST['update'])) {

    $new_quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $new_total = $price * $new_quantity;

    $sql = "UPDATE cart SET qty = '$new_quantity', total_price = '$new_total' WHERE id = $item_id";
    $result = mysqli_query($conn, $sql);

    //check if update process is true
    if ($result == TRUE) {

        header("Location: add-purchase-orders.php");
    } else {

        $_SESSION['failed-to-update'] = "Failed To Update Item Quantity Cart.";
    }
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Add Orders</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

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
    include('accountant-navbar.php'); ?>

    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="manage-purchase-orders.php">Manage Purchase Orders</a></li>
                    <li class="breadcrumb-item active">Add Orders</li>
                </ol>
                <?php if ($update == true) : ?>
                    <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-shopping-cart"></i></span> Update Order</h1>
                <?php else : ?>
                    <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-shopping-cart"></i></span> Add Orders</h1>
                <?php endif ?>
                <a href="manage-purchase-orders.php" class="btn app-btn btn-info" style="color:white"><i class="fa fa-list"></i> Order List</a>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['add-item'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['add-item']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['add-item']);
                }
                if (isset($_SESSION['remove-item'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['remove-item']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['remove-item']);
                }
                if (isset($_SESSION['item-already-exist'])) {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['item-already-exist']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['item-already-exist']);
                }
                if (isset($_SESSION['failed-to-remove'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-remove']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['failed-to-remove']);
                }
                if (isset($_SESSION['failed-to-update'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-update']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['failed-to-update']);
                }
                if (isset($_SESSION['required'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['required']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['required']);
                }
                ?>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <div class="mb-3">
                                        <fieldset class="form-group">
                                            <label for="setting-input-3" class="form-label">Material Name: </label>
                                            <?php if ($update == true) : ?>
                                                <input id="requirement_name" type="text" name="requirement_name" class="form-control" required readonly value="<?php echo $product; ?>">
                                            <?php else : ?>
                                                <select id="material" name="material" class="form-select" onchange='fetch_select(this.value)' required>
                                                    <option disabled selected>-- Choose Material -- </option>
                                                    <?php
                                                    $sql = "SELECT * FROM materials ORDER by name ASC";
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
                                                        <option value="0">No Material Found</option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            <?php endif ?>
                                        </fieldset>
                                    </div>
                                    <input id="name" type="hidden" name="name" class="form-control" required readonly>
                                    <input id="id" type="hidden" name="id" class="form-control" required readonly>
                                    <div class="mb-3">
                                        <label for="setting-input-2" class="form-label">Desciption: </label>
                                        <input id="description" type="text" name="supplier" <?php if ($update == true) : ?> value="<?php echo $description; ?>" <?php endif ?> class="form-control" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-2" class="form-label">Supplier: </label>
                                        <input id="supplier" type="text" name="supplier" <?php if ($update == true) : ?> value="<?php echo $supplier; ?>" <?php endif ?>class="form-control" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-2" class="form-label">Unit: </label>
                                        <input id="unit" type="text" name="unit" <?php if ($update == true) : ?> value="<?php echo $unit; ?>" <?php endif ?>class="form-control" required readonly>
                                    </div>
                                    <input id="unit" type="hidden" name="unit" class="form-control" required readonly>
                                    <div class="mb-3">
                                        <label for="setting-input-2" class="form-label">Price: </label>
                                        <input id="price" type="text" name="price" <?php if ($update == true) : ?> value="<?php echo $price; ?>" <?php endif ?>class="form-control" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-2" class="form-label">Quantity: </label>
                                        <input id="price" type="number" name="quantity" <?php if ($update == true) : ?> value="<?php echo $quantity; ?>" <?php endif ?>class="form-control" autocomplete="off" min="0" required>
                                    </div>
                                    <?php if ($update == true) : ?>
                                        <button type="submit" name="update" class="btn app-btn btn-info" style="color:white">Update Quantity</button>
                                    <?php else : ?>
                                        <button type="submit" name="submit" class="btn app-btn-primary"><i class="fas fa-shopping-cart"></i> Add To Cart</button>
                                    <?php endif ?>
                                    <a href="add-purchase-orders.php" class="btn app-btn btn-info" style="background-color:grey; float:right; color:white">Cancel</a>
                                </form>
                            </div>
                            <!--//app-card-body-->
                        </div>
                        <!--//app-card-->
                    </div>

                    <div class="col-12 col-md-8">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="clearfix">
                                <h1 class="app-page-title" style="float:left"><span class="nav-icon"><i class="fa fa-shopping-cart"></i> Order Cart</h1>
                                <?php

                                $sql = "SELECT * FROM cart";
                                $result = mysqli_query($conn, $sql);
                                $count = mysqli_num_rows($result);

                                ?>
                                <p class="btn app-btn btn-dark" style="background-color:black; color:white; float:right">You have <b style="color:yellow"><?php echo $count; ?></b> item/s in your cart</p>
                            </div>
                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <div class="table-responsive mb-3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="cell text-center" style="border:1px solid #000">Material</th>
                                                    <th class="cell text-center" style="width: 15%; border:1px solid #000">Price</th>
                                                    <th class="cell text-center" style="border:1px solid #000">Quantity
                                                        <button type="submit" name="update" class="btn app-btn btn-info" style="color:white; cursor:pointer; display:none"><i class="fas fa-save"></i></button>
                                                    </th>
                                                    <th class="cell text-center" style="width: 15%; border:1px solid #000">Amount</th>
                                                    <th class="cell text-center" style="border:1px solid #000" colspan="2">
                                                        <a href="add-purchase-orders.php?remove=all" class="btn app-btn btn-danger w-100" style="color:white" onclick="return confirm('Are You Sure You Want To Remove All Items In The Cart?')"><i class="fas fa-trash"></i> Remove All</a>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM materials, cart WHERE cart.product_id = materials.id";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if worker record are existing in db
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $id = $row['id'];
                                                        $order_id = $row['order_id'];
                                                        $product_name = $row['product_name'];
                                                        $product_price = $row['product_price'];
                                                        $unit = $row['unit'];
                                                        $quantity = $row['qty'];
                                                        $amount = $row['total_price'];

                                                ?>
                                                        <tr>
                                                            <td class="cell" style="border:1px solid #000; padding-top: 0.5em"><?php echo $product_name; ?></td>
                                                            <input type="hidden" class="form-control pid" value="<?php $id; ?>">
                                                            <td class="cell" style="border:1px solid #000; padding-top: 0.5em">₱<?php echo number_format($product_price, 2, '.', ','); ?></td>
                                                            <input type="hidden" class="form-control pprice" value="<?php $product_price; ?>">
                                                            <td class="cell" style="border:1px solid #000; padding-top: 0.5em"><input type="text" class="form-control" name="new_quantity" autocomplete="off" required style=" width:75px" readonly value="<?php echo $quantity . " " . $unit; ?>">
                                                            </td>
                                                            <td class="cell" style="border:1px solid #000; padding-top: 0.5em">₱<?php echo number_format($amount, 2, '.', ','); ?></td>
                                                            <td class="cell text-center" style="border:1px solid #000; padding-top: 0.5em">
                                                                <a href="add-purchase-orders.php?ID=<?php echo $id; ?>" class="btn app-btn btn-info" style="color:white"><i class="fa fa-edit"></i></a>
                                                            </td>
                                                            <td class="cell text-center" style="border:1px solid #000">
                                                                <a href="add-purchase-orders.php?delete=<?php echo $id; ?>" class="btn app-btn btn-danger" style="color:white" onclick="return confirm('Are You Sure You Want To Remove Item From The Cart?')"><i class="fa fa-times"></i></a>
                                                            </td>

                                                        </tr>
                                                    <?php
                                                        $cost += $amount;
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <td class="cell text-center" colspan="7" style="border:1px solid #000; font-weight: bold; color:red">No Orders Found!</td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                                <tr>
                                                    <td class="cell" colspan="2" style="text-align:right; border:1px solid #000; font-weight: bold">Total </td>
                                                    <td class="cell text-right" colspan="2" style="text-align:right;border:1px solid #000; font-weight: bold">₱<?php echo number_format($cost, 2, '.', ','); ?></td>
                                                    <td class="cell text-center" colspan="2" style="border:1px solid #000; font-weight: bold">
                                                        <a href="checkout.php?checkout=active" class="btn app-btn-primary w-100 <?= ($cost > 1) ? "" : "disabled" ?>" style="padding-top: 
                                                        0.5em;color:white">Place Order</a>
                                                    </td>
                                                </tr>
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

</body>

<!-- fetch material details script -->
<script>
    function fetch_select(val) {
        $.ajax({
            url: "fetch-material.php",
            type: "POST",
            data: {
                "get_option": val
            },
            dataType: "JSON",
            success: function(data) {
                $('#id').val((data[0].id));
                $('#name').val((data[0].name));
                $('#supplier').val((data[0].supplier));
                $('#description').val((data[0].description));
                $('#unit').val((data[0].unit));
                $('#price').val((data[0].price));
            }

        });
    }
</script>

<script>
    setTimeout(function() {
        document.getElementById("alert").style.display = "none";
    }, 3000);
</script>

</html>