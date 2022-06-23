<?php

include('../connections/config.php');

error_reporting(0);

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM staffs WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are account records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if account exist
    if ($count == 1) {
    } else {

        $_SESSION['account-not-found'] = "Account Not Found.";
        header("Location: manage-account.php");
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['account-not-found'] = "Account Not Found.";
    header("Location: manage-account.php");
}

if (isset($_POST['submit'])) {

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $bday = mysqli_real_escape_string($conn, $_POST['bday']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $civil_status = mysqli_real_escape_string($conn, $_POST['civil_status']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $current_image = mysqli_real_escape_string($conn, $_POST['current_image']);
    $activity = "Update Account Details for " . $full_name;

    if (isset($_FILES['profile']['name']) && ($_FILES['profile']['name'] !== "")) {
        $size = $_FILES['profile']['size'];
        $temp = $_FILES['profile']['tmp_name'];
        $type = $_FILES['profile']['type'];
        $image_name = $_FILES['profile']['name'];
        // delete old file from the folder
        unlink("../staff_images/$current_image");
        //new image in the folder
        move_uploaded_file($temp, "../staff_images/$image_name");
    } else {
        $image_name = $current_image;
    }

    $sql = "UPDATE staffs SET full_name = '$full_name', birthday = '$bday', age = '$age', gender = '$gender', address = '$address', contact = '$contact', civil_status = '$civil_status', profile = '$image_name' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    //check if update process if true
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

        $sql = "SELECT * FROM staffs WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        $_SESSION['update-account'] = " Profile Details Updated Successfully!";
    } else {

        $_SESSION['failed-to-update'] = "Failed to Update Profile Details.";
    }
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Update Profile</title>

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
    <link rel="stylesheet" href="assets/plugins/jquery-ui-1.13.0/jquery-ui.css">
    <script src="assets/plugins/jquery-ui-1.13.0/external/jquery/jquery.js"></script>
    <script src="assets/plugins/jquery-ui-1.13.0/jquery-ui.js"></script>

    <!-- used to prevent data to insert again when page is refreshed-->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>

<body class="app">

    <?php include('engineer-navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-user"></i></span> Update Profile</h1>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['update-account'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['update-account']; ?> </strong>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    unset($_SESSION['update-account']);
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
                ?>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">
                        <div class="app-card app-card-settings shadow-sm p-4">

                            <div class="app-card-body">
                                <form class="settings-form" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Full Name: </label>
                                        <input type="text" name="full_name" class="form-control" id="setting-input-3" value="<?php echo $row['full_name']; ?>" autocomplete="off" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Birthday: </label>
                                        <input type="text" id="date" name="bday" class="form-control" id="setting-input-3" value="<?php echo $row['birthday']; ?>" autocomplete="off" required>
                                    </div>
                                    <div class="mb-3">
                                        <fieldset class="form-group">
                                            <label for="setting-input-3" class="form-label">Gender: </label>
                                            <select name="gender" class="form-select" id="basicSelect" required>
                                                <option value="Male" <?php echo ($row['gender'] == "Male") ? 'selected' : ''; ?>>Male</option>
                                                <option value="Female" <?php echo ($row['gender'] == "Female") ? 'selected' : ''; ?>>Female</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Address: </label>
                                        <input type="text" name="address" class="form-control" id="setting-input-3" value="<?php echo $row['address']; ?>" autocomplete="off" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Contact Number: </label>
                                        <input type="number" name="contact" class="form-control" id="setting-input-3" value="<?php echo $row['contact']; ?>" autocomplete="off" required>
                                    </div>
                                    <div class="mb-3">
                                        <fieldset class="form-group">
                                            <label for="setting-input-3" class="form-label">Civil Status: </label>
                                            <select name="civil_status" class="form-select" id="basicSelect" required>
                                                <option value="Single" <?php echo ($row['civil_status'] == "Single") ? 'selected' : ''; ?>>Single</option>
                                                <option value="Married" <?php echo ($row['civil_status'] == "Married") ? 'selected' : ''; ?>>Married</option>
                                                <option value="Divorced" <?php echo ($row['civil_status'] == "Divorced") ? 'selected' : ''; ?>>Divorced</option>
                                                <option value="Separated" <?php echo ($row['civil_status'] == "Separated") ? 'selected' : ''; ?>>Separated</option>
                                                <option value="Widowed" <?php echo ($row['civil_status'] == "Widowed") ? 'selected' : ''; ?>>Widowed</option>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Current Profile Picture : </label><br>
                                        <img src="../staff_images/<?php echo $row['profile']; ?>" alt="" style="width:120px;height:120px">
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">Image Preview : </label><br>
                                        <img id="preimage" style="width:120px;height:120px">
                                    </div>
                                    <div class="mb-3">
                                        <label for="setting-input-3" class="form-label">New Profile Picture: </label>
                                        <input type="file" name="profile" class="form-control" id="image" onchange="loadfile(event)">
                                    </div>
                                    <input type="hidden" name="current_image" value="<?php echo $row['profile']; ?>">
                                    <button type="submit" name="submit" class="btn app-btn-primary">Update</button>
                                    <a href="manage-account.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
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

<script>
    $(function() {
        $('#date').datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            maxDate: 0,
            yearRange: '1950:2021'
        });
    });
</script>

<script>
    function loadfile(event) {
        var output = document.getElementById('preimage');
        output.src = URL.createObjectURL(event.target.files[0]);
    };
</script>

<script>
    setTimeout(function() {
        document.getElementById("alert").style.display = "none";
    }, 3000);
</script>

</html>