<?php

include('../connections/config.php');

//check if user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM staffs, projects WHERE staffs.id = projects.engineer_id AND projects.id = '$id'";
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
//check if page is not forcefully accessed
if ($id == "") {

	$_SESSION['project-not-found'] = "Project Not Found.";
	header("Location: manage-projects.php");
}

if (isset($_POST['submit'])) {

	$type = mysqli_real_escape_string($conn, $_POST['type']);
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$description = mysqli_real_escape_string($conn, $_POST['description']);
	$engineer_id = mysqli_real_escape_string($conn, $_POST['engineer_id']);
	$location = mysqli_real_escape_string($conn, $_POST['location']);
	$clientname = mysqli_real_escape_string($conn, $_POST['client']);
	$start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
	$end_date = mysqli_real_escape_string($conn, $_POST['end_date']);

	$diff = strtotime($end_date) - strtotime($start_date);
	$days = round($diff / 86400);

	$status = mysqli_real_escape_string($conn, $_POST['status']);
	$activity = "Update Project Details of " . $name;

	$sql = "UPDATE projects SET type = '$type', name = '$name', project_description = '$description', engineer_id = '$engineer_id', location = '$location', client_name = '$clientname', start_date = '$start_date', end_date = '$end_date', status = '$status' WHERE id = '$id'";
	$result = mysqli_query($conn, $sql);

	//check if update process if true
	if ($result == TRUE) {

		$sql = "UPDATE teams SET working_days = '$days' WHERE project_id = '$id'";
		$result = mysqli_query($conn, $sql);

		//update last activity date and time
		if ($result = TRUE) {
		} else {
			echo "<script>alert('Error in Updating Last Activity')</script>";
		}

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

		$sql = "SELECT * FROM projects WHERE id = '$id'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);

		$_SESSION['update-projects'] = "Project Details Updated Successfully!";
		header("Location: manage-projects.php");
	} else {

		$_SESSION['failed-to-update'] = "Failed to Update Project Details.";
	}
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Update Project</title>

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

	<link rel="stylesheet" href="assets/plugins/chosen/chosen.min.css">
	<script src="assets/plugins/chosen/chosen.jquery.min.js"></script>

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
					<li class="breadcrumb-item active">Update Project</li>
				</ol>
				<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-building"></i></span> Update Project</h1>
				<hr class="mb-4">
				<!-- alert messages -->
				<?php
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
								<form class="settings-form" method="post">
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Project Type: </label>
											<select name="type" class="form-select" id="basicSelect" required>
												<option value="Commercial Construction" <?php echo ($row['type'] == "Commercial Construction") ? 'selected' : ''; ?>>Commercial Construction</option>
												<option value="Residential Construction" <?php echo ($row['type'] == "Residential Construction") ? 'selected' : ''; ?>>Residential Construction</option>
												<option value="Industrial Construction" <?php echo ($row['type'] == "Industrial Construction") ? 'selected' : ''; ?>>Industrial Construction</option>
											</select>
										</fieldset>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Name: </label>
										<input type="text" name="name" class="form-control" id="setting-input-3" value="<?php echo $row['name']; ?>" autocomplete="off" required>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Description: </label>
										<textarea name="description" style="height:5.5em" class="form-control" required><?php echo $row['project_description']; ?></textarea>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Engineer Name: </label>
											<select name="engineer_id" class="form-select chosen" required>
												<option value="<?php echo $row['engineer_id']; ?>"><?php echo $row['full_name']; ?></option>
												<?php
												$sql = "SELECT * FROM staffs WHERE access = 'Engineer'";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												//check if engineer exists
												if ($count > 0) {
													while ($row = mysqli_fetch_assoc($result)) {
														$id = $row['id'];
														$full_name = $row['full_name'];
												?>
														<option value="<?php echo $id; ?>"><?php echo $full_name; ?></option>
													<?php
													}
												} else {
													?>
													<option value="">No Engineer Found</option>
												<?php
												}
												?>
											</select>
										</fieldset>
									</div>
									<?php

									$sql = "SELECT * FROM projects WHERE id = '$id'";
									$result = mysqli_query($conn, $sql);
									$row = mysqli_fetch_assoc($result);

									?>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Location: </label>
										<input type="text" name="location" class="form-control" id="setting-input-3" value="<?php echo $row['location']; ?>" autocomplete="off" required>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Client Name: </label>
											<select name="client" class="form-select" id="basicSelect" required>
												<option value="<?php echo $row['client_name']; ?>"><?php echo $row['client_name']; ?></option>
												<?php
												$sql = "SELECT * FROM clients";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												//check if clients exist
												if ($count > 0) {
													while ($row = mysqli_fetch_assoc($result)) {
														$name = $row['name'];
												?>
														<option value="<?php echo $name; ?>"><?php echo $name; ?></option>
													<?php
													}
												} else {
													?>
													<option value="">No Client Found</option>
												<?php
												}
												?>
											</select>
										</fieldset>
									</div>
									<?php

									$sql = "SELECT * FROM projects WHERE id = '$id'";
									$result = mysqli_query($conn, $sql);
									$row = mysqli_fetch_assoc($result);

									?>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Start Date: </label>
										<input type="text" id="startDate" name="start_date" class="form-control" id="setting-input-2" value="<?php echo $row['start_date']; ?>" autocomplete="off" required>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">End Date: </label>
										<input type="text" id="endDate" name="end_date" class="form-control" id="setting-input-2" value="<?php echo $row['end_date']; ?>" autocomplete="off" required>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Project Status: </label>
											<select name="status" class="form-select" id="basicSelect">
												<option value="Started" <?php echo ($row['status'] == "Started") ? 'selected' : ''; ?>>Started</option>
												<option value="On Hold" <?php echo ($row['status'] == "On Hold") ? 'selected' : ''; ?>>On Hold</option>
												<option value="Finished" <?php echo ($row['status'] == "Finished") ? 'selected' : ''; ?>>Finished</option>
												<option value="Cancelled" <?php echo ($row['status'] == "Cancelled") ? 'selected' : ''; ?>>Cancelled</option>
											</select>
										</fieldset>
									</div>
									<button type="submit" name="submit" class="btn app-btn-primary">Update</button>
									<a href="project-details.php?ID=<?php echo $id ?>" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
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
		var start = $("#startDate").datepicker({
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true
			}).on("change", function() {
				end.datepicker("option", "minDate", getDate(this));
			}),

			end = $("#endDate").datepicker({
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true
			}).on("change", function() {
				start.datepicker("option", "maxDate", getDate(this));
			});

		function getDate(element) {
			var date;
			var dateFormat = 'yy-mm-dd';
			try {
				date = $.datepicker.parseDate(dateFormat, element.value);
			} catch (error) {
				date = null;
			}
			return date
		}
	});
</script>

<script>
	setTimeout(function() {
		document.getElementById("alert").style.display = "none";
	}, 3000);
</script>

</html>