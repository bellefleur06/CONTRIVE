<?php

include('../connections/config.php');

$page = 'dashboard';

if (!isset($_SESSION['username'])) {

    header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CONTRIVE | Admin Dashboard</title>

    <!-- Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link id="theme-style" rel="stylesheet" href="dataTables/jquery.dataTables.min.css">
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />
    <link rel="stylesheet" href="../assets/css/style.css" />

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="app">

    <?php include('engineer-navbar.php'); ?>

    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="position-relative mb-3">
                <div class="row g-3 justify-content-between">
                    <div class="col-auto">
                        <h1 class="app-page-title mb-0">Dashboard</h1>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-3">

                <div class="col-6 col-lg-3">
                    <a href="manage-projects.php">
                        <div class="app-card app-card-stat shadow-sm h-100">
                            <br />
                            <span class="nav-icon"><i class="fa fa-user fa-5x text-success"></i></span>
                            <div class="app-card-body p-3 p-lg-4">
                                <?php

                                $sql = "SELECT * FROM clients, projects WHERE projects.client_name = clients.name and projects.engineer_id = '{$_SESSION['id']}'";
                                $result = mysqli_query($conn, $sql);
                                $count = mysqli_num_rows($result);

                                ?>
                                <h4 class="stats-type mb-1">Clients</h4>
                                <div class="stats-figure"><?php echo $count; ?></div>
                            </div>
                            <!--//app-card-body-->
                        </div>
                    </a>
                    <!--//app-card-->
                </div>

                <div class="col-6 col-lg-3">
                    <a href="manage-projects.php">
                        <div class="app-card app-card-stat shadow-sm h-100">
                            <br />
                            <span class="nav-icon"><i class="fa fa-building fa-5x text-success"></i></span>
                            <div class="app-card-body p-3 p-lg-4">
                                <?php

                                $sql = "SELECT * FROM projects WHERE projects.engineer_id = '{$_SESSION['id']}'";
                                $result = mysqli_query($conn, $sql);
                                $count = mysqli_num_rows($result);

                                ?>
                                <h4 class="stats-type mb-1">Projects</h4>
                                <div class="stats-figure"><?php echo $count; ?></div>
                            </div>
                            <!--//app-card-body-->
                        </div>
                    </a>
                    <!--//app-card-->
                </div>

                <div class="col-6 col-lg-3">
                    <a href="workers.php">
                        <div class="app-card app-card-stat shadow-sm h-100">
                            <br />
                            <span class="nav-icon"><i class="fa fa-users fa-5x text-success"></i></span>
                            <div class="app-card-body p-3 p-lg-4">
                                <?php

                                $sql = "SELECT * FROM workers, teams, projects WHERE workers.id = teams.member_id AND projects.id = teams.project_id AND projects.engineer_id = '{$_SESSION['id']}'";
                                $result = mysqli_query($conn, $sql);
                                $count = mysqli_num_rows($result);

                                ?>
                                <h4 class="stats-type mb-1">On-Site Workers</h4>
                                <div class="stats-figure"><?php echo $count; ?></div>
                            </div>
                            <!--//app-card-body-->
                        </div>
                    </a>
                    <!--//app-card-->
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

                $('#2ndTable').DataTable({
                    "aaSorting": [],
                    "sScrollX": "100%",
                    "sScrollXInner": "100%",
                    "bScrollCollapse": true
                });

                $('#3rdTable').DataTable({
                    "aaSorting": [],
                    "sScrollX": "100%",
                    "sScrollXInner": "100%",
                    "bScrollCollapse": true
                });

                $('#4thTable').DataTable({
                    "aaSorting": [],
                    "sScrollX": "100%",
                    "sScrollXInner": "100%",
                    "bScrollCollapse": true
                });

            });
        </script>

        <script>
            const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Divisions',
                    data: [100, 95, 90, 88, 86, 75, 40, 36, 25, 20, 18, 65],
                    backgroundColor: '#eebf41',
                    borderColor: '#d26d69',
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            grace: '5%',
                        }
                    }
                },
            };

            Chart.defaults.font.size = 14;
            var myChart = new Chart(
                document.getElementById('myChart'),
                config
            );
        </script>

        <!-- Page Specific JS -->
        <script src="assets/js/app.js"></script>
</body>

</html>