<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM workers WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are worker records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if worker exist
    if ($count == 1) {

        $worker_id = $row['id'];
    } else {

        $_SESSION['worker-not-found'] = "Worker Data Not Found.";
        header("Location: worker-reports.php");
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['worker-not-found'] = "Worker Data Not Found.";
    header("Location: worker-reports.php");
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

    <link id="theme-style" rel="stylesheet" href="dataTables/jquery.dataTables.min.css">
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="../assets/css/portal.css">
    <link rel="stylesheet" href="../assets/css/style.css" />

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                                <a href="print-worker-report.php?ID=<?php echo $id; ?>" target="_blank" class="btn app-btn btn-info mb-3" style="color:white"><i class=" fa fa-print"></i> Print Report Details</a>
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table class="table app-table-hover text-left">
                                            <tr>
                                                <td class="cell" colspan="2">
                                                    <h1 class="app-page-title">Worker Report Details</h1>
                                                </td>
                                                <td>
                                                    <a href="worker-reports.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Name:</b> <?php echo $row['first_name'] . " " . $row['last_name']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Address:</b> <?php echo $row['address']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Birthday:</b> <?php echo $date = date("M d, Y", strtotime($row['birthday'])); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Age:</b> <?php echo $row['age']; ?> yrs old
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Contact No.:</b> <?php echo $row['contact']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Civil Status:</b> <?php echo $row['civil_status']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Position:</b> <?php echo $row['position']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Rate Per Hour:</b> ₱<?php echo $row['rate']; ?>
                                                </td>
                                                <td class="cell py-3">
                                                    <b>Hours Per Shift:</b> <?php echo $row['hours_per_day']; ?> hrs
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="cell py-3">
                                                    <b>Date Added:</b> <?php echo $date = date("M d, Y", strtotime($row['date_added'])); ?>
                                                </td>
                                                <td class="cell py-3" colspan="2">
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

                    <div class="col-12 col-lg-12">
                        <div class="row g-4 settings-section">
                            <div class="col-12 col-md-12">
                                <div class="app-card app-card-settings shadow-sm p-4">
                                    <div class="app-card-body">
                                        <table id="myTable" class="table app-table-hover text-center">
                                            <h1 class="app-page-title">Worker's Projects Participation</h1>
                                            <hr>
                                            <thead>
                                                <tr>
                                                    <th>Project</th>
                                                    <th>Engineer</th>
                                                    <th>Position</th>
                                                    <th>Working Dates</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $sql = "SELECT * FROM staffs, teams, workers, projects WHERE staffs.id = projects.engineer_id AND teams.project_id = projects.id AND teams.member_id = workers.id AND workers.id = '$worker_id'";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {

                                                        $project_name = $row['name'];
                                                        $full_name = $row['full_name'];
                                                        $position = $row['position'];
                                                        $start_date = $row['start_date'];
                                                        $end_date = $row['end_date'];
                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 0.5em">
                                                                <?php echo $project_name; ?>
                                                            </td>
                                                            <td class="cell" style="padding-top: 0.5em">
                                                                <?php echo $full_name; ?>
                                                            </td>
                                                            <td class="cell" style="padding-top: 0.5em">
                                                                <?php echo $position; ?>
                                                            </td>
                                                            <td class="cell" style="padding-top: 0.5em">
                                                                <?php echo $date = date("M d, Y", strtotime($row['start_date'])) . " to " . $date = date("M d, Y", strtotime($row['end_date'])); ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr>
                                                        <th class=" cell pt-4" colspan="5">
                                                            <h1 class="app-page-title" style="text-align:center; color:#d26d69; font-size:2rem;">No Projects Found!</h1>
                                                        </th>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--//app-card-body-->

                                </div>
                                <!--//app-card-->
                            </div>
                        </div>
                    </div>

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

</html>