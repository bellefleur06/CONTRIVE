<?php

include('../connections/config.php');

error_reporting(0);

//check if user is logged in
if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | User Logs</title>

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

    <?php include('navbar.php'); ?>

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> User Logs</h1>
                <hr class="mb-4">
                <div class="row g-4 settings-section">
                    <div class="col-12 col-md-12">
                        <div class="app-card app-card-settings shadow-sm p-4">
                            <div class="app-card-body">
                                <form class="settings-form">
                                    <div class="mb-3">
                                        <table id="myTable" class="table app-table-hover mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">Name</th>
                                                    <th class="cell">Login Time</th>
                                                    <th class="cell">Last Activity</th>
                                                    <th class="cell">Logout Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM staffs WHERE last_login != '0000-00-00 00:00:00'";
                                                $result = mysqli_query($conn, $sql);
                                                $count = mysqli_num_rows($result);

                                                //check if clients exist
                                                if ($count > 0) {

                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $full_name = $row['full_name'];
                                                        $last_login = $row['last_login'];
                                                        $login = date("M d, Y - h:i a", strtotime($last_login));
                                                        $last_activity = $row['last_activity'];
                                                        $activity = date("M d, Y - h:i a", strtotime($last_activity));
                                                        $last_logout = $row['last_logout'];
                                                        $logout = date("M d, Y - h:i a", strtotime($last_logout));
                                                ?>
                                                        <tr>
                                                            <td class="cell" style="padding-top: 1em"><?php echo $full_name; ?></td>
                                                            <td class="cell" style="padding-top: 1em"><?php echo $login; ?></td>
                                                            <td class="cell" style="padding-top: 1em"><?php echo $activity; ?></td>
                                                            <td class="cell" style="padding-top: 1em"><?php echo $logout; ?></td>
                                                        </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<script>alert('No User Logs Found!')</script>";
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

    <!-- add client modal -->
    <div id="addclientModal" class="modal fade" tabindex="-1" aria-labelledby="enroll" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="add-form">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Client</h4>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="setting-input-3" class="form-label">Name: </label>
                            <input type="text" name="name" class="form-control" autocomplete="off" placeholder="Full Name" required value="<?php echo $_POST['name']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="setting-input-3" class="form-label">Contact No.: </label>
                            <input type="number" name="contact" class="form-control" autocomplete="off" placeholder="Mobile Phone or Landline" required value="<?php echo $_POST['contact']; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="setting-input-3" class="form-label">last_lgout$last_logout: </label>
                            <input type="email" name="email" class="form-control" autocomplete="off" placeholder="Google Mail or Yahoo Mail" required value="<?php echo $_POST['email']; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn app-btn-primary" name="add" id="add">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- delete button script -->
    <script>
        function deleteClient(id) {
            if (confirm('Are You Sure You Want To Delete This Client?')) {
                $.ajax({
                    type: 'POST',
                    url: 'delete-clients.php',
                    data: {
                        delete_id: id
                    },
                    success: function(data) {
                        $('#delete' + id).hide();
                    }
                });
            }
        }
    </script>

    <!-- update client modal -->
    <div id="updateclientModal" class="modal fade" tabindex="-1" aria-labelledby="enroll" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Client Details</h4>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" class="form-control" id="id" required>
                        <div class="mb-3">
                            <label for="setting-input-3" class="form-label">Name: </label>
                            <input type="text" name="client_name" class="form-control" id="client_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="setting-input-3" class="form-label">Contact No.: </label>
                            <input type="number" name="client_contact" class="form-control" id="client_contact" required>
                        </div>
                        <div class="mb-3">
                            <label for="setting-input-3" class="form-label">Email: </label>
                            <input type="email" name="client_email" class="form-control" id="client_email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn app-btn-primary" name="update">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- update button script -->
    <script>
        $(document).ready(function() {

            $(document).on('click', '.update-clients', function() {
                var client_id = $(this).attr("id");

                $.ajax({
                    url: "fetch-clients.php",
                    method: "POST",
                    data: {
                        client_id: client_id
                    },
                    dataType: "json",
                    success: function(data) {

                        $('#client_name').val(data.name);
                        $('#client_contact').val(data.contact);
                        $('#client_email').val(data.email);
                        $('#id').val(data.id);

                        $('#updateclientModal').modal('show');
                    }
                });
            });
        });
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

</html>