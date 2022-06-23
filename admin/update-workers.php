<?php

include('../connections/config.php');

error_reporting(0);

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

if (isset($_POST['submit'])) {

	$last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
	$first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
	$middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
	$bday = mysqli_real_escape_string($conn, $_POST['bday']);

	$today = date("Y-m-d");
	$diff =  date_diff(date_create($bday), date_create($today));

	$age = $diff->format('%y');

	$gender = mysqli_real_escape_string($conn, $_POST['gender']);
	$address = mysqli_real_escape_string($conn, $_POST['address']);
	$contact = mysqli_real_escape_string($conn, $_POST['contact']);
	$civil_status = mysqli_real_escape_string($conn, $_POST['civil_status']);
	$position_id = mysqli_real_escape_string($conn, $_POST['position_id']);
	$position_name = mysqli_real_escape_string($conn, $_POST['position_name']);
	$rate = mysqli_real_escape_string($conn, $_POST['rate']);
	$hours_per_day = mysqli_real_escape_string($conn, $_POST['hours']);
	$status = mysqli_real_escape_string($conn, $_POST['status']);
	$current_image = mysqli_real_escape_string($conn, $_POST['current_image']);
	$activity = "Update Worker Details For " . $first_name . " " . $last_name . " - " . $position_name;

	if (isset($_FILES['profile']['name']) && ($_FILES['profile']['name'] !== "")) {

		$size = $_FILES['profile']['size'];
		$temp = $_FILES['profile']['tmp_name'];
		$type = $_FILES['profile']['type'];
		$image_name = $_FILES['profile']['name'];
		// delete old file from the folder
		unlink("../worker_images/$current_image");
		//new image in the folder
		move_uploaded_file($temp, "../worker_images/$image_name");
	} else {

		$image_name = $current_image;
	}

	$sql = "UPDATE workers SET last_name = '$last_name', first_name = '$first_name', middle_name = '$middle_name', birthday = '$bday', age = '$age', gender = '$gender', address = '$address', contact = '$contact', civil_status = '$civil_status', position_id = '$position_id', position = '$position_name', rate = '$rate', hours_per_day = '$hours_per_day', profile = '$image_name', status = '$status' WHERE id = '$id'";
	$result = mysqli_query($conn, $sql);

	//check if update process if true
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

		$sql = "SELECT * FROM workers WHERE id = '$id'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);

		$_SESSION['update-workers'] = "Worker Details Updated Successfully!";
	} else {

		$_SESSION['failed-to-update'] = "Failed to Update Worker Details.";
	}
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Update Workers</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT">
	<meta http-equiv="pragma" content="no-cache">

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
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item"><a href="manage-workers.php">Manage Workers</a></li>
					<li class="breadcrumb-item active">Update Worker</li>
				</ol>
				<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-user"></i></span> Update Worker</h1>
				<hr class="mb-4">
				<!-- alert messages -->
				<?php
				if (isset($_SESSION['update-workers'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['update-workers']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['update-workers']);
				}
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
								<form class="settings-form" method="post" enctype="multipart/form-data">
									<div class="mb-3">
										<label for="setting-input-2" class="form-label">Last Name: </label>
										<input type="text" name="last_name" class="form-control" id="setting-input-2" value="<?php echo $row['last_name']; ?>" autocomplete="off" required>
									</div>
									<div class="mb-3">
										<label for="setting-input-2" class="form-label">First Name: </label>
										<input type="text" name="first_name" class="form-control" id="setting-input-2" value="<?php echo $row['first_name']; ?>" autocomplete="off" required>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Middle Name:</label>
										<input type="text" name="middle_name" class="form-control" id="setting-input-3" value="<?php echo $row['middle_name']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Birthday: </label>
										<input type="text" id="date" name="bday" class="form-control" id="setting-input-3" value="<?php echo $row['birthday']; ?>" autocomplete="off" required>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Gender: </label>
											<select name="gender" class="form-select" id="basicSelect" required>
												<option value="Male" <?php echo ($row['gender'] == "Male") ? 'selected' : ''; ?>>Male</option>
												<option value="Female" <?php echo ($row['gender'] == "Female") ? 'selected' : ''; ?>>Female</option>
											</select>
										</fieldset>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Address: </label>
										<input type="text" name="address" class="form-control" id="setting-input-3" value="<?php echo $row['address']; ?>" autocomplete="off" required>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Contact Number: </label>
										<input type="number" name="contact" class="form-control" id="setting-input-3" value="<?php echo $row['contact']; ?>" autocomplete="off" required>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Civil Status: </label>
											<select name="civil_status" class="form-select" id="basicSelect" required>
												<option value="Single" <?php echo ($row['civil_status'] == "Single") ? 'selected' : ''; ?>>Single</option>
												<option value="Married" <?php echo ($row['civil_status'] == "Married") ? 'selected' : ''; ?>>Married</option>
												<option value="Divorced" <?php echo ($row['civil_status'] == "Divorced") ? 'selected' : ''; ?>>Divorced</option>
												<option value="Separated" <?php echo ($row['civil_status'] == "Separated") ? 'selected' : ''; ?>>Separated</option>
												<option value="Widowed" <?php echo ($row['civil_status'] == "Widowed") ? 'selected' : ''; ?>>Widowed</option>
											</select>
										</fieldset>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Position: </label>
											<select id="position_id" name="position_id" class="form-select" onchange='fetch_select(this.value)' required>
												<option value="<?php echo $row['position_id']; ?>"><?php echo $row['position']; ?></option>
												<?php
												$sql = "SELECT * FROM positions ORDER BY position ASC";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												if ($count > 0) {
													while ($row = mysqli_fetch_assoc($result)) {
														$id = $row['id'];
														$position = $row['position'];
												?>
														<option value="<?php echo $id; ?>"><?php echo $position; ?></option>
													<?php
													}
												} else {
													?>
													<option value="0">No Position Found!</option>
												<?php
												}
												?>
											</select>
										</fieldset>
									</div>
									<?php

									$id = $_GET['ID'];

									$sql = "SELECT * FROM workers WHERE id = '$id'";
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

									?>
									<input id="position" type="hidden" name="position_name" class="form-control" value="<?php echo $row['position']; ?>" readonly>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Daily Rate: </label>
										<input id="rate" type="text" name="rate" class="form-control" id="setting-input-3" value="<?php echo $row['rate']; ?>" autocomplete="off" readonly>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">No. of Working Hours Per Day:</label>
										<input id="rate" type="text" name="hours" class="form-control" id="setting-input-3" value="<?php echo $row['hours_per_day']; ?>" autocomplete="off" required>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Status: </label>
											<select name="status" class="form-select" id="basicSelect" required>
												<option value="Active" <?php echo ($row['status'] == "Active") ? 'selected' : ''; ?>>Active</option>
												<option value="Inactive" <?php echo ($row['status'] == "Inactive") ? 'selected' : ''; ?>>Inactive</option>
											</select>
										</fieldset>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Current Profile Picture : </label><br>
										<img src="../worker_images/<?php echo $row['profile']; ?>" alt="" style="width:120px;height:120px">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Image Preview : </label><br>
										<img id="preimage" style="width:120px;height:120px">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">New Profile Picture: </label>
										<input type="file" name="profile" class="form-control" id="image" onchange="loadfile(event)" autocomplete="off">
									</div>
									<input type="hidden" name="current_image" value="<?php echo $row['profile']; ?>">
									<button type="submit" name="submit" class="btn app-btn-primary">Update</button>
									<a href="worker-details.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
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

	<!-- fetch position details script -->
	<script>
		function fetch_select(val) {
			$.ajax({
				url: "fetch-position.php",
				type: "POST",
				data: {
					"get_option": val
				},
				dataType: "JSON",
				success: function(data) {
					$('#position').val((data[0].position));
					$('#rate').val((data[0].rate));
				}

			});
		}
	</script>

</body>

<script>
	$(function() {
		$('#date').datepicker({
			dateFormat: "yy-mm-dd",
			changeMonth: true,
			changeYear: true,
			maxDate: 0,
			yearRange: '1950:2021'
		});
	});
</script>

<script>
	function loadfile(event) {
		var output = document.getElementById('preimage');
		output.src = URL.createObjectURL(event.target.files[0]);
	};
</script>

<script>
	setTimeout(function() {
		document.getElementById("alert").style.display = "none";
	}, 3000);
</script>

</html>