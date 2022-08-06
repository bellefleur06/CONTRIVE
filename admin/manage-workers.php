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
	<title>CONTRIVE | Manage Workers</title>

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

	<?php $page = 'worker';
	include('navbar.php'); ?>

	<div class="app-wrapper">
		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item active">Manage Workers</li>
				</ol>
				<h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Manage Workers</h1>
				<a href="add-workers.php" class="btn app-btn-primary" style="margin-bottom: 0.5em"><i class="fa fa-plus"></i> New Worker</a>
				<a href="manage-worker-positions.php" class="btn app-btn btn-secondary" style="margin-bottom: 0.5em; color:white"><i class="fas fa-briefcase"></i> Manage Worker Positions</a>
				<a href="print-workers.php" target="_blank" class="btn app-btn btn-info" style="margin-bottom: 0.5em; color:white"><i class=" fa fa-print"></i> Print List</a>
				<hr class="mb-4">
				<!-- alert messages -->
				<?php
				if (isset($_SESSION['add-workers'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['add-workers']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['add-workers']);
				}
				if (isset($_SESSION['delete-workers'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['delete-workers']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['delete-workers']);
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
				if (isset($_SESSION['worker-not-found'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['worker-not-found']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['worker-not-found']);
				}
				?>
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
													<th class="cell">Working for a Project?</th>
													<th class="cell">Status</th>
													<th class="cell">Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$sql = "SELECT * FROM workers ORDER by emp_id";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												//check if worker record are existing in db
												if ($count > 0) {

													while ($row = mysqli_fetch_assoc($result)) {
														$emp_id = $row['emp_id'];
														$last_name = $row['last_name'];
														$first_name = $row['first_name'];
														$position = $row['position'];
														$assigned = $row['assigned'];
														$profile = $row['profile'];
														$status = $row['status'];
												?>
														<tr>
															<td><img src="../worker_images/<?php echo $profile; ?>" alt="" style="width:100px;height:80px"></td>
															<td class="cell" style="padding-top: 1em"><?php echo $emp_id; ?></td>
															<td class="cell" style="padding-top: 1em"><?php echo $first_name . " " . $last_name; ?></td>
															<td class="cell" style="padding-top: 1em"><?php echo $position; ?></td>
															<?php
															//check if worker is working for a project
															if ($row['assigned'] == "Yes") {
															?>
																<td class="cell text-center" style="padding-top: 1em; font-weight:bold; color:green">Yes</td>
															<?php
																//check if worker is not working for a project
															} else if ($row['assigned'] == "No") {
															?>
																<td class="cell text-center" style="padding-top: 1em; font-weight:bold; color:blue">No</td>
															<?php
															}
															?>
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
															<td>
																<a href="worker-details.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-info" style="color:white; margin-top: 0.1em"><i class="fa fa-eye"></i> Profile</a>
															</td>
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