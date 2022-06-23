<?php

include('../connections/config.php');

error_reporting(0);

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = 0;
$edit = false;

//check if add button is clicked 
if (isset($_POST['submit'])) {

    $name =  mysqli_real_escape_string($conn, $_POST['name']);
    $activity = "Add New Supplier Category - " . $name;

    $sql =  "SELECT name FROM categories WHERE name = '$name'";
    $result = mysqli_query($conn, $sql);

    //check if category already exist
    if (!$result->num_rows > 0) {

        $sql = "INSERT INTO categories (name) VALUES ('$name')";
        $result = mysqli_query($conn, $sql);

        //check if insert result is true
        if ($result == TRUE) {

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

            $_SESSION['add-categories'] = "Category Added Successfully!";

            //clear texboxes if the result is true
            $_POST['name'] = "";
        } else {

            $_SESSION['failed-to-add'] = "Failed to Add Category.";
        }
    } else {
        $_SESSION['category-already-exist'] = "Category Already Exist.";
    }
}

//edit button
if (isset($_GET['ID'])) {

    $category_id = $_GET['ID'];
    $edit = true;

    $sql = "SELECT * FROM categories WHERE id = $category_id;";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($result == TRUE) {

        $count = mysqli_num_rows($result);

        if ($count == 1) {
            $name = $row['name'];
        }
    }
}

//check if update button is clicked
if (isset($_POST['update'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $activity = "Update Category Name of " . $name;

    $sql = "UPDATE categories SET name = '$name' WHERE id = '$category_id'";
    $result = mysqli_query($conn, $sql);

    //check if update process if true
    if ($result == TRUE) {

        $sql = "UPDATE suppliers SET category_name = '$name' WHERE category_id = $category_id";
        $result = mysqli_query($conn, $sql);

        //update last activity date and time
        if ($result = TRUE) {
        } else {
            echo "<script>alert('Error in Updating Last Activity')</script>";
        }

        $sql = "UPDATE materials SET category_name = '$name' WHERE category_id = $category_id";
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

        $sql = "SELECT * FROM categories WHERE id = '$category_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        $_SESSION['update-categories'] = "Category Name Updated Successfully!";
    } else {

        $_SESSION['failed-to-update'] = "Failed to Update Category Name.";
    }
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Manage Categories</title>

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

    <?php $page = 'supplier';
    include('navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="manage-suppliers.php">Manage Suppliers</a></li>
                    <li class="breadcrumb-item active">Manage Categories</li>
                </ol>
                <h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Manage Categories</h1>
                <a href="manage-suppliers.php" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-list"></i> Suppliers</a>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['add-categories'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['add-categories']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['add-categories']);
                }
                if (isset($_SESSION['delete-categories'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['delete-categories']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['delete-categories']);
                }
                if (isset($_SESSION['failed-to-delete'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-delete']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['failed-to-delete']);
                }
                if (isset($_SESSION['failed-to-update'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-update']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['failed-to-update']);
                }
                if (isset($_SESSION['category-not-found'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['category-not-found']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['category-not-found']);
                }
                if (isset($_SESSION['update-categories'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['update-categories']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['update-categories']);
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
                if (isset($_SESSION['category-already-exist'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['category-already-exist']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['category-already-exist']);
                }
                ?>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <?php if ($edit == true) : ?>
                            <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-list"></i></span> Update Category</h1>
                        <?php else : ?>
                            <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-list"></i></span> Add Categories</h1>
                        <?php endif ?>
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Category: </label>
                                        <input type="text" name="name" class="form-control" <?php if ($edit == true) : ?> value="<?php echo $name; ?>" <?php endif ?> id="setting-input-3" autocomplete="off" value="<?php echo $_POST['name']; ?>" required>
                                    </div>
                                    <?php if ($edit == true) : ?>
                                        <button type="submit" name="update" class="btn app-btn-primary">Update</button>
                                        <a href="manage-categories.php" class="btn app-btn-primary" style="float:right;background-color:grey">Cancel</a>
                                    <?php else : ?>
                                        <button type="submit" name="submit" class="btn app-btn-primary">Add</button>
                                    <?php endif ?>
                                </form>
                            </div>
                            <!--//app-card-body-->
                        </div>
                        <!--//app-card-->
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form">
                                    <div class="mb-3">
                                        <table id="myTable" class="table app-table-hover mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">Category</th>
                                                    <th class="cell">Actions</th>
                                                    <th class="cell"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM categories";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if categories exist
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $id = $row['id'];
                                                        $name = $row['name'];
                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 0.5em"><?php echo $name; ?></td>
                                                            <td><a href="manage-categories.php?ID=<?php echo $id; ?>" class="btn app-btn btn-info" style="color:white"><i class="fa fa-edit"></i> Edit</a></td>
                                                            <td><a href="delete-categories.php?ID=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to remove this category record?');" class="btn app-btn btn-danger" style="color:white"><i class="fa fa-trash"></i> Delete</a></td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<script>alert('No Categories Found!')</script>";
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