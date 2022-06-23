<?php

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime, new DateTimeZone('Asia/Manila'));
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

include('../connections/config.php');

if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$project_id = $_GET['ID'];
$update_id = $_GET['update'];

$sql = "SELECT * FROM updates, staffs WHERE updates.user_id = staffs.id AND project_id = '$project_id' AND update_id = '$update_id'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are project records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if project exist
    if ($count == 1) {

        $update_id =  $row['update_id'];
        $project_id =  $row['project_id'];
        $division_name =  $row['division_name'];
        $progress =  $row['progress'];
        $details =  $row['details'];
        $user_id =  $row['user_id'];
        $date_posted =  $row['date_posted'];
    } else {

        $_SESSION['project-not-found'] = "Project Not Found.";
        header("Location: manage-projects.php");
    }
}

//check if page is not forcefully accessed
if ($project_id == "" and $update_id = "") {

    $_SESSION['project-not-found'] = "Project Not Found.";
    header("Location: manage-projects.php");
}



?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Project Divisions</title>

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

    <?php $page = 'project';
    include('navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form">
                                    <table class="w-100">
                                        <h5 style="color:#5b99ea"><?php echo $division_name . " - " . $progress . "% (Total Progress)"; ?></h5>
                                        <hr>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p><i class="fas fa-calendar"> </i>
                                                        <i> <?php echo $date = date("d F Y", strtotime($date_posted)); ?> <span style="color:#bbb; padding-left:5px;"><?php echo time_elapsed_string($date_posted); ?></span></i>
                                                    </p>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p><b>Posted by: </b>
                                                        <a style="color:#5b99ea">
                                                            <?php
                                                            echo $row['full_name'];
                                                            ?>
                                                        </a>
                                                    </p>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p><?php echo $details; ?></p>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="project-details.php?ID=<?php echo $project_id; ?>" class="btn app-btn btn-info" style="color:white; width: 100px;float:right "><i class="fa fa-arrow-left"></i> Go Back</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <!--//app-card-body-->
                        </div>
                        <!--//app-card-->
                    </div>
                </div>
                <!--//row-->
                <hr class="mb-4">
            </div>
            <!--//container-fluid-->
        </div>
        <!--//app-content-->
    </div>
    <!--//app-wrapper-->

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
                    $('#name').val((data[0].name));
                    $('#category_id').val((data[0].category_id));
                    $('#category_name').val((data[0].category_name));
                    $('#description').val((data[0].description));
                    $('#unit').val((data[0].unit));
                    $('#stocks').val((data[0].stocks));
                    $('#price').val((data[0].price));
                }

            });
        }
    </script>

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