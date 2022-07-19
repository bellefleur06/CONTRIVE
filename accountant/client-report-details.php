<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT *, COUNT(*) as count FROM projects, clients WHERE projects.client_name = clients.name AND clients.id = '$id' AND clients.status = '1' GROUP BY clients.name";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are worker records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if worker exist
    if ($count == 1) {

        $client_name = $row['name'];
    } else {

        $_SESSION['client-not-found'] = "Client Data Not Found.";
        header("Location: client-reports.php");
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['client-not-found'] = "Client Data Not Found.";
    header("Location: client-reports.php");
}
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Report Details</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
    <link rel="stylesheet" href="../assets/css/style.css" />

</head>

<body class="app">

    <?php $page = 'report'; 
    include('accountant-navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-lg-12">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <a href="print-client-report.php?ID=<?php echo $id; ?>" target="_blank" class="btn app-btn btn-info mb-3" style="color:white"><i class=" fa fa-print"></i> Print Report Details</a>
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <?php
                                        
                                        $id = $_GET['ID'];

                                        $sql = "SELECT *, COUNT(*) as count FROM projects, clients WHERE projects.client_name = clients.name AND clients.id = '$id' AND clients.status = '1' GROUP BY clients.name";
                                        $result = mysqli_query($conn, $sql);
                                        $row = mysqli_fetch_assoc($result);

                                        ?>
                                        <table class="table app-table-hover text-left">
                                            <tr>
                                                <td class="cell" colspan="2">
                                                    <h1 class="app-page-title"> Client Report Details</h1>
                                                </td>
                                                <td>
                                                    <a href="client-reports.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Name:</b> <?php echo $row['client_name']; ?>
                                                </td>
                                                <td class="cell py-3" colspan="2">
                                                    <b>Address:</b> <?php echo $row['address']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Contact No.:</b> <?php echo $row['contact']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Email:</b> <?php echo $row['email']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Date Added:</b> <?php echo $date = date("M d, Y", strtotime($row['date_added'])); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Status:</b>
                                                    <?php if ($row['status'] == '1') 
                                                        {
                                                            echo 'Active';
                                                        } else {
                                                            echo 'Inactive';
                                                        }?>
                                                </td>
                                                <td class="cell py-3" colspan="2">
                                                    <b>No. of Requested Projects:</b>
                                                    <?php echo $row['count']; ?>
                                                </td>
                                            </tr>
                                        </table>

                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>
                        </div>
                    </div>
                    <!--//row-->

                    <?php

                    $sql = "SELECT * FROM staffs, projects WHERE projects.engineer_id = staffs.id AND projects.client_name = '$client_name'";
                    $result = mysqli_query($conn, $sql);
                    $count = mysqli_num_rows($result);

                    //check if projects exist
                    if ($count > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {

                    ?>

                            <div class="col-12 col-lg-12">
                                <div class="row g-4 settings-section">
                                    <div class="col-12 col-md-12">
                                        <div class="app-card app-card-settings shadow-sm p-4">
                                            <div class="app-card-body">
                                                <table class="table app-table-hover text-left">
                                                    <tr>
                                                        <td class="cell" colspan="3">
                                                            <h1 class="app-page-title"> Client's Requested Project Details</h1>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="cell py-3">
                                                            <b>Project Name:</b> <?php echo $row['name']; ?>
                                                        </td>
                                                        <td class="cell py-3">
                                                            <b>Type:</b> <?php echo $row['type']; ?>
                                                        </td>
                                                        <td class="cell py-3">
                                                            <b>Location:</b> <?php echo $row['location']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="cell py-3">
                                                            <b>Engineer:</b> <?php echo $row['full_name']; ?>
                                                        </td>
                                                        <td class="cell py-3" colspan="2">
                                                            <b>Description:</b> <?php echo $row['project_description']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="cell py-3">
                                                            <b>Start Date:</b> <?php echo $date = date("M d, Y", strtotime($row['start_date'])); ?>
                                                        </td>
                                                        <td class="cell py-3">
                                                            <b>End Date:</b> <?php echo $date = date("M d, Y", strtotime($row['end_date'])); ?>
                                                        </td>
                                                        <td class="cell py-3">
                                                            <b>Status:</b> <?php echo $row['status']; ?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <!--//app-card-body-->

                                        </div>
                                        <!--//app-card-->
                                    </div>
                                </div>
                            </div>

                    <?php
                        }
                    }
                    ?>

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

</html>