<?php

include('../connections/config.php');

error_reporting(0);

if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM projects WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are project records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if project exist
    if ($count == 1) {

        $project_id = $row['id'];
    } else {

        $_SESSION['project-not-found'] = "Project Not Found.";
        header("Location: manage-projects.php");
    }
}

//check if page is not forcefully accessed
if ($id == "") {

    $_SESSION['project-not-found'] = "Project Not Found.";
    header("Location: manage-projects.php");
}

$sql = "SELECT DATEDIFF(end_date, start_date) As days FROM projects WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are project records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if project exist
    if ($count == 1) {
    } else {

        $_SESSION['project-not-found'] = "Project Not Found.";
        header("Location: manage-projects.php");
    }
}

$days = $row['days'];

$id = $_GET['ID'];

$sql = "SELECT * FROM projects WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are project records
if ($result == TRUE) {

    $count = mysqli_num_rows($result);

    //check if project exist
    if ($count == 1) {
    } else {

        $_SESSION['project-not-found'] = "Project Not Found.";
        header("Location: manage-projects.php");
    }
}

$project_name = $row['name'];

//assign workers to project
if (isset($_POST['add'])) {

    $project_id = $id;
    $project_name = $row['name'];
    $position_name_id =  mysqli_real_escape_string($conn, $_POST['position']);
    $member_id =  mysqli_real_escape_string($conn, $_POST['member']);

    if (empty($position_name_id) || empty($member_id)) {

        $_SESSION['required'] = "All Fields Are Required.";
    } else {

        $sql =  "SELECT member_id FROM teams WHERE member_id = '$member_id' AND project_id = '$project_id'";
        $result = mysqli_query($conn, $sql);

        //check if worker already exist
        if (!$result->num_rows > 0) {

            $sql = "INSERT INTO teams (project_id, project, position_id, member_id, working_days) VALUES ('$project_id', '$project_name', '$position_name_id', '$member_id', '$days')";
            $result = mysqli_query($conn, $sql);

            if ($result == TRUE) {

                $_SESSION['assign-member'] = "Member Assigned Successfully.";

                $_POST['position'] = "";
                $_POST['member'] = "";
            } else {

                $_SESSION['failed-to-assign'] = "Failed to Assign Member.";
            }
        } else {

            $_SESSION['member-already-assigned'] = "Member Already Assigned.";
        }
    }
}

//remove workers
if (isset($_GET['ID']) && isset($_GET['delete'])) {

    $worker_id = $_GET['delete'];

    $sql = "SELECT * FROM teams WHERE id = '$worker_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //check if there are worker records
    if ($result == TRUE) {

        $count = mysqli_num_rows($result);

        //check if worker exist
        if ($count == 1) {
        } else {

            $_SESSION['project-not-found'] = "Project Not Found.";
            header("Location: manage-projects.php");
        }
    }

    $project_name = $row['project'];
    $member_id = $row['member_id'];

    $sql = "SELECT * FROM workers WHERE id = '$member_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    //check if there are worker records
    if ($result == TRUE) {

        $count = mysqli_num_rows($result);

        //check if worker exist
        if ($count == 1) {
        } else {

            $_SESSION['project-not-found'] = "Project Not Found.";
            header("Location: manage-projects.php");
        }
    }

    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $position = $row['position'];
    $activity = "Removed Project Worker For " . $project_name . " - " . $first_name . " " . $last_name . " - " . $position;

    $sql = "DELETE FROM teams WHERE id = '$worker_id'";
    $result = mysqli_query($conn, $sql);

    //check if delete process is true
    if ($result == TRUE) {

        // $_SESSION['remove-member'] = "Member Removed Successfully!";
        // header("Location: project-workers.php?ID=$project_id");

        echo "<script>alert('Member Removed Successfully!');window.location.replace('project-workers.php?ID=$project_id');</script>";

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
    } else {

        // echo "<script>alert('Failed To Remove Member.')</script>";
        // $_SESSION['failed-to-remove'] = "Failed To Remove Member.";

        echo "<script>alert('Failed To Remove Member.');window.location.replace('project-workers.php?ID=$project_id');</script>";
    }
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Project Workers</title>

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
                <ol class="breadcrumb mb-4" style="float:right">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="manage-projects.php">Manage Projects</a></li>
                    <li class="breadcrumb-item active"><a href="project-details.php?ID=<?php echo $id; ?>">Project Details</a></li>
                    <li class="breadcrumb-item active">Project Workers</li>
                </ol>
                <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-users"></i></span> "<?php echo $row['name']; ?>" Project Workers</h1>
                <a href="project-details.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-arrow-left"></i> Go Back</a>
                <hr class="mb-4">
                <!-- alert messages -->
                <?php
                if (isset($_SESSION['assign-member'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['assign-member']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['assign-member']);
                }
                if (isset($_SESSION['failed-to-assign'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-assign']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['failed-to-assign']);
                }
                if (isset($_SESSION['member-already-assigned'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['member-already-assigned']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['member-already-assigned']);
                }
                if (isset($_SESSION['remove-member'])) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['remove-member']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['remove-member']);
                }
                if (isset($_SESSION['failed-to-remove'])) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['failed-to-remove']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['failed-to-remove']);
                }
                if (isset($_SESSION['required'])) {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
                        <strong> <?php echo $_SESSION['required']; ?> </strong>
                    </div>
                <?php
                    unset($_SESSION['required']);
                }
                ?>
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-4">
                        <h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-user"></i></span> Add Worker</h1>
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <div class="mb-3">
                                        <fieldset class="form-group">
                                            <label for="setting-input-3" class="form-label">Member Position: </label>
                                            <select id="position" name="position" class="form-select" required>
                                                <option disabled selected>-- Select Member Position --</option>
                                                <?php
                                                $sql = "SELECT * FROM positions ORDER by position ASC";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                if ($count > 0) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $position_id = $row['id'];
                                                        $position_name = $row['position'];
                                                ?>
                                                        <option value="<?php echo $position_id; ?>"><?php echo $position_name; ?></option>
                                                    <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <option value="0">No Position Found</option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="mb-3">
                                        <fieldset class="form-group">
                                            <label for="setting-input-3" class="form-label">Member Name: </label>
                                            <select id="member" name="member" class="form-select" required>
                                                <option disabled selected>-- Select Member --</option>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <button type="submit" name="add" class="btn app-btn btn-info" style="color:white">Assign</button>
                                </form>
                            </div>
                            <!--//app-card-body-->
                        </div>
                        <!--//app-card-->
                    </div>

                    <?php
                    $sql = "SELECT * FROM projects WHERE id = '$id'";
                    $result = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result);
                    ?>

                    <div class="col-12 col-md-8">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form" method="post">
                                    <div class="mb-3">
                                        <table id="myTable" class="table app-table-hover mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">Position</th>
                                                    <th class="cell">Member Name</th>
                                                    <th class="cell">Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM workers, teams WHERE workers.position_id = teams.position_id AND workers.id = teams.member_id AND project_id = '" . $row['id'] . "'";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if worker record are existing in db
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $id = $row['id'];
                                                        $position_name = $row['position'];
                                                        $first_name = $row['first_name'];
                                                        $last_name = $row['last_name'];
                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 0.5em"><?php echo $position_name; ?></td>
                                                            <td class="cell" style="padding-top: 0.5em"><?php echo $first_name . " " . $last_name; ?></td>
                                                            <td>
                                                                <a href="project-workers.php?ID=<?php echo $project_id; ?>&delete=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to unassign this project worker?');" class="btn app-btn btn-danger" style="color:white"><i class="fa fa-times"></i></a>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    // echo "<script>alert('No Project Workers Found!')</script>";
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
            $('#position').change(function() {
                var position_id = $(this).val();
                $.ajax({
                    url: "fetch-worker.php",
                    method: "POST",
                    data: {
                        positionId: position_id
                    },
                    dataType: "text",
                    success: function(data) {
                        $('#member').html(data);
                    }
                });
            });
        });
    </script>

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