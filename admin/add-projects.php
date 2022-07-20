<?php

include('../connections/config.php');

error_reporting(0);

//check if user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

//check if add button is clicked
if (isset($_POST['submit'])) {

	$type =  mysqli_real_escape_string($conn, $_POST['type']);
	$name =  mysqli_real_escape_string($conn, $_POST['name']);
	$description = mysqli_real_escape_string($conn, $_POST['description']);
	$engineer_id = mysqli_real_escape_string($conn, $_POST['engineer_id']);
	$location = mysqli_real_escape_string($conn, $_POST['location']);
	$clientname =  mysqli_real_escape_string($conn, $_POST['client']);
	$start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
	$end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
	$status = "On Hold";
	$activity = "Add New Project - " . $name;

	if (empty($type) or empty($engineer_id) or empty($clientname)) {

		$_SESSION['required'] = "All Fields Are Required.";
	} else {

		$sql = "SELECT type, name, project_description, engineer_id, location, client_name FROM projects WHERE type = '$type' AND name = '$name' AND project_description = '$description' AND project_engineer = '$engineer_id' AND location = '$location' AND client_name = '$clientname'";
		$result = mysqli_query($conn, $sql);

		//check if project already exist
		if (!$result->num_rows > 0) {

			$contract_file = $_FILES['contract_file']['name'];
			$file_temp = $_FILES['contract_file']['tmp_name'];
			$allowed_ext = array("pdf");
			$exp = explode(".", $contract_file);
			$ext = end($exp);
			$path = "../documents/" .$contract_file;

			$blueprint_file = $_FILES['blueprint_file']['name'];
			$file_temp = $_FILES['blueprint_file']['tmp_name'];
			$allowed_ext = array("pdf");
			$exp = explode(".", $blueprint_file);
			$ext = end($exp);
			$path = "../documents/" .$blueprint_file;
	
			//check if there is a file to be uploaded
			if ($contract_file  == "" or $blueprint_file == "") {
	
				$_SESSION['select-file'] = "Please Select Contract or Blueprint File!";
	
				//check if the file is an image
			} else if (in_array($ext, $allowed_ext)) {

				if (move_uploaded_file($file_temp, $path)) {

					$sql = "INSERT INTO projects (type, name, project_description, engineer_id, location, client_name, start_date, end_date, contract, blueprint, status) VALUES ('$type', '$name', '$description', '$engineer_id', '$location', '$clientname', '$start_date', '$end_date', '$contract_file', '$blueprint_file', '$status')";
					$result = mysqli_query($conn, $sql);
	
					//check if insert process is true
					if ($result == TRUE) {
	
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
	
						$_SESSION['add-projects'] = "Project Added Successfully!";
						header("Location: manage-projects.php");
	
						//clear textboxes if result is true
						$_POST['type'] = "";
						$_POST['name'] = "";
						$_POST['engineer'] = "";
						$_POST['location'] = "";
						$_POST['client'] = "";
						$_POST['start_date'] = "";
						$_POST['end_date'] = "";
					} else {
	
						$_SESSION['failed-to-add'] = "Failed to Add Project.";
					}
					
				} else {
					
					$_SESSION['upload-failed'] = "file Not Uploaded!";
				}
			} else {
					$_SESSION['invalid-file-format'] = "Invalid File Format! File should be on 'PDF' Format Only.";
			}
		} else {
			$_SESSION['project-already-exist'] = "Project Already Exist.";
		}
	}
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Add Projects</title>

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
					<li class="breadcrumb-item active">Add Projects</li>
				</ol>
				<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-building"></i></span> Add Projects</h1>
				<hr class="mb-4">
				<!-- alert messages -->
				<?php
				if (isset($_SESSION['required'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['required']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['required']);
				}
				if (isset($_SESSION['failed-to-add'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['failed-to-add']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['failed-to-add']);
				}
				if (isset($_SESSION['project-already-exist'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['project-already-exist']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['project-already-exist']);
				}
				if (isset($_SESSION['select-file'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['select-file']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['select-file']);
				}
				if (isset($_SESSION['invalid-file-format'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['invalid-file-format']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['invalid-file-format']);
				}
				if (isset($_SESSION['upload-failed'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['upload-failed']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['upload-failed']);
				}
				?>
				<div class="row g-4 settings-section">
					<div class="col-12 col-md-12">
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<form class="settings-form" method="post" enctype="multipart/form-data">
									<div class="pb-5">
										<a href="manage-projects.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-list"></i> Project List</a>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Project Type: </label>
											<select name="type" class="form-select" id="chosen" required>
												<option disabled selected>-- Select Project Type --</option>
												<option value="Commercial Construction">Commercial Construction</option>
												<option value="Residential Construction">Residential Construction</option>
												<option value="Industrial Construction">Industrial Construction</option>
											</select>
										</fieldset>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Name: </label>
										<input type="text" name="name" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['name']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Description: </label>
										<textarea name="description" style="height:5.5em" class="form-control" required><?php echo $_POST['description']; ?></textarea>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Engineer Name: </label>
											<select name="engineer_id" class="form-select chosen" required>
												<option disabled selected>-- Select Engineer Name --</option>
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
														<option value="<?php echo $id; ?>"><?php echo $full_name ?></option>
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
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Location: </label>
										<input type="text" name="location" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['location']; ?>">
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Client Name: </label>
											<select name="client" class="form-select chosen" required>
												<option disabled selected>-- Select Client Name --</option>
												<?php
												$sql = "SELECT * FROM clients";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												//check if client exists
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
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Start Date: </label>
										<input type="text" id="startDate" name="start_date" class="form-control" placeholder="yyyy-mm-dd" autocomplete="off" required value="<?php echo $_POST['start_date']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">End Date: </label>
										<input type="text" id="endDate" name="end_date" class="form-control" placeholder="yyyy-mm-dd" autocomplete="off" required value="<?php echo $_POST['end_date']; ?>">
									</div>
									<div class="mb-3">
										<div class="row">
											<div class="col-12 col-md-6">
												<label for="setting-input-3" class="form-label">Project Contract: </label>
												<input type="file" name="contract_file" class="form-control" autocomplete="off" required value="<?php echo $_POST['contract_file']; ?>">
											</div>
											<div class="col-12 col-md-6">
												<label for="setting-input-3" class="form-label">Project Blueprint: </label>
												<input type="file" name="blueprint_file" class="form-control" autocomplete="off" required value="<?php echo $_POST['blueprint_file']; ?>">
											</div>
										</div>
									</div>
									<button type="submit" name="submit" class="btn app-btn-primary">Add</button>
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

<!-- <script>
	$("#chosen").chosen();
	$(".chosen").chosen();
</script> -->

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