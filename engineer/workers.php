<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | On-Site Workers</title>

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


</head>

<body class="app">

	<?php $page = 'workers';
	include('engineer-navbar.php'); ?>

	<div class="app-wrapper">

		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item active">On-Site Workers</li>
				</ol>
				<h1 class="app-page-title"><span class=" nav-icon"><i class="fa fa-list"></i></span> On-Site Workers</h1>
				<a href="print-projects.php" target="_blank" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-print"></i> Print List</a>
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
                                                <th class="cell"></th>
													<th class="cell">ID Number</th>
													<th class="cell">Full Name</th>
													<th class="cell">Position</th>
													<th class="cell">Status</th>
												</tr>
											</thead>
											<tbody>
                                            <?php
												$sql = "SELECT * FROM teams, projects, workers WHERE workers.id = teams.member_id AND projects.id = teams.project_id AND projects.engineer_id = '{$_SESSION['id']}'";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												//check if worker record are existing in db
												if ($count > 0) {

													while ($row = mysqli_fetch_assoc($result)) {
														$emp_id = $row['emp_id'];
														$last_name = $row['last_name'];
														$first_name = $row['first_name'];
														$position = $row['position'];
														$rate = $row['rate'];
														$profile = $row['profile'];
														$status = $row['assigned'];
												?>
														<tr>
															<td><img src="../worker_images/<?php echo $profile; ?>" alt="" style="width:100px;height:80px"></td>
															<td class="cell" style="padding-top: 1em"><?php echo $emp_id; ?></td>
															<td class="cell" style="padding-top: 1em"><?php echo $first_name . " " . $last_name; ?></td>
															<td class="cell" style="padding-top: 1em"><?php echo $position; ?></td>
															<?php
															//check if worker status is active
															if ($row['status'] == "Active") {
                                                                ?>
																<td class="cell" style="padding-top: 1em; font-weight:bold; color:green">Active</td>
                                                                <?php
																//check if worker status is inactive
															} else if ($row['status'] == "Inactive") {
                                                                ?>
																<td class="cell" style="padding-top: 1em; font-weight:bold; color:red">Inactive</td>
                                                                <?php
															}
															?>
														</tr>
												<?php
													}
												} else {
													echo "<script>alert('No Workers Found!')</script>";
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