<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM workers WHERE workers.id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are worker records
if ($result == TRUE) {

	$count = mysqli_num_rows($result);

	//check if worker exist
	if ($count == 1) {
	} else {

		$_SESSION['worker-not-found'] = "Worker Not Found.";
		header("Location: manage-workers.php");
	}
}

//check if page is not forcefully accessed
if ($id == "") {

	$_SESSION['worker-not-found'] = "Worker Not Found.";
	header("Location: manage-workers.php");
}
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Employee Details</title>

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

	<?php include('navbar.php'); ?>

	<div class="app-wrapper">

		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-user"></i></span> "<?php echo $row['first_name'] . " " . $row['last_name']; ?>" Details</h1>
				<hr class="mb-4">
				<div class="row g-4 mb-4">
					<div class="col-auto">
						<div class="app-card shadow-sm ">
							<div class="row">
								<img src="../worker_images/<?php echo $row['profile']; ?>" alt="" style="width:300px;height:300px;">
							</div>
						</div>
					</div>
					<div class="col-12 col-lg">
						<div class="row g-4 settings-section">
							<div class="col-12 col-md-12">
								<div class="app-card app-card-settings shadow-sm p-4">
									<div class="app-card-body">
										<table class="table app-table-hover text-left">
											<!-- <tr>
										<center>
											<img src="../worker_images/<?php echo $row['profile']; ?>" alt="" style="width:300px;height:300px; border-radius:50%;">
										</center>
									</tr> -->
											<tr>
												<td class="cell">
													<label for="setting-input-2" class="form-label">Full Name: </label>
												</td>
												<td class="cell">
													<label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name']; ?></label>
												</td>
											</tr>
											<tr>
												<td class="cell">
													<label for="setting-input-2" class="form-label">Birthday: </label>
												</td>
												<td class="cell">
													<label for="setting-input-3" class="form-label" style="color:#363636">
														<?php
														$birthday = $row['birthday'];
														echo $date = date("M d, Y", strtotime($birthday));
														?>
													</label>
												</td>
											</tr>
											<tr>
												<td class="cell">
													<label for="setting-input-2" class="form-label">Gender: </label>
												</td>
												<td class="cell">
													<label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['gender']; ?></label>
												</td>

											</tr>
											<tr>
												<td class="cell">
													<label for="setting-input-2" class="form-label">Address: </label>
												</td>
												<td class="cell">
													<label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['address']; ?></label>
												</td>
											</tr>
											<tr>
												<td class="cell">
													<label for="setting-input-2" class="form-label">Contact No.: </label>
												</td>
												<td class="cell">
													<label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['contact']; ?></label>
												</td>
											</tr>
											<tr>
												<td class="cell">
													<label for="setting-input-2" class="form-label">Civil Status: </label>
												</td>
												<td class="cell">
													<label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['civil_status']; ?></label>
												</td>
											</tr>
											<tr>
												<td class="cell">
													<label for="setting-input-2" class="form-label">Position: </label>
												</td>
												<td class="cell">
													<label for="setting-input-3" class="form-label" style="color:#363636"><?php echo $row['position']; ?></label>
												</td>
											</tr>
											<tr>
												<td class="cell">
													<label for="setting-input-2" class="form-label">Daily Rate: </label>
												</td>
												<td class="cell">
													<label for="setting-input-3" class="form-label" style="color:#363636">₱<?php echo $row['rate']; ?></label>
												</td>
											</tr>
											<tr>
												<?php
												if ($row['status'] == "Active") {
												?>
													<td>
														<label for="setting-input-2" class="form-label">Status: </label>
													</td>
													<td class="cell">
														<label for="setting-input-3" class="form-label" style="color:green"><?php echo $row['status']; ?></label>
													</td>
												<?php
												} else if ($row['status'] == "Inactive") {
												?>
													<td class="cell">
														<label for="setting-input-2" class="form-label">Status: </label>
													</td>
													<td class="cell">
														<label for="setting-input-3" class="form-label" style="color:red"><?php echo $row['status']; ?></label>
													</td>
												<?php
												}
												?>
											</tr>
											<tr>
												<?php
												if ($row['assigned'] == "Yes") {
												?>
													<td>
														<label for="setting-input-2" class="form-label">Working for a Project? </label>
													</td>
													<td class="cell">
														<label for="setting-input-3" class="form-label" style="color:green"><?php echo $row['assigned']; ?></label>
													</td>
													<?php
														$sql = "SELECT * FROM staffs, projects, teams, workers WHERE staffs.id = projects.engineer_id AND projects.id = teams.project_id AND teams.member_id = workers.id AND workers.id = '$id'";
														$result = mysqli_query($conn, $sql);
														$row = mysqli_fetch_assoc($result);
													?>
													<tr>
														<td>
															<label for="setting-input-2" class="form-label">Project: </label>
														</td>
														<td class="cell">
															<label for="setting-input-3" class="form-label"><?php echo $row['project']; ?></label>
														</td>
													</tr>
													<tr>
														<td>
															<label for="setting-input-2" class="form-label">Engineer: </label>
														</td>
														<td class="cell">
															<label for="setting-input-3" class="form-label"><?php echo $row['full_name']; ?></label>
														</td>
													</tr>
												<?php
												} else if ($row['assigned'] == "No") {
												?>
													<td class="cell">
														<label for="setting-input-2" class="form-label">Assigned: </label>
													</td>
													<td class="cell">
														<label for="setting-input-3" class="form-label" style="color:blue"><?php echo $row['assigned']; ?></label>
													</td>
												<?php
												}
												?>
											</tr>
										</table>
										<form class="settings-form" action="delete-workers.php" method="post">
											<a href="update-workers.php?ID=<?php echo $row['id']; ?>" class="btn app-btn-primary"><i class="fa fa-edit"></i> Edit</a>
											<input type="hidden" name="ID" value="<?php echo $row['id']; ?>">
											<input type="hidden" name="profile" value="<?php echo $row['profile']; ?>">
											<button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this worker record?');" class="btn app-btn btn-danger" style="color:white">Delete</button>
											<a href="manage-workers.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
										</form>
									</div>
									<!--//app-card-body-->

								</div>
								<!--//app-card-->
							</div>
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

</html>