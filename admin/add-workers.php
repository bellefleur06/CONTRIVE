<?php

include('../connections/config.php');

error_reporting(0);

//check if user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

//check if add button is clicked 
if (isset($_POST['submit'])) {

	$emp_id = "EMP-" . rand(000, 999);
	$last_name =  mysqli_real_escape_string($conn, $_POST['last_name']);
	$first_name =  mysqli_real_escape_string($conn, $_POST['first_name']);
	$middle_name =  mysqli_real_escape_string($conn, $_POST['middle_name']);
	$bday = mysqli_real_escape_string($conn, $_POST['bday']);

	$date_added = date("Y-m-d");
	$diff =  date_diff(date_create($bday), date_create($date_added));

	$age = $diff->format('%y');

	$gender =  mysqli_real_escape_string($conn, $_POST['gender']);
	$address = mysqli_real_escape_string($conn, $_POST['address']);
	$contact = mysqli_real_escape_string($conn, $_POST['contact']);
	$civil_status = mysqli_real_escape_string($conn, $_POST['civil_status']);
	$position_id = mysqli_real_escape_string($conn, $_POST['position_id']);
	$position_name = mysqli_real_escape_string($conn, $_POST['position_name']);
	$rate = mysqli_real_escape_string($conn, $_POST['rate']);
	$hours_per_day = mysqli_real_escape_string($conn, $_POST['hours']);
	$status = "Active";
	$assigned =  "No";
	$date_added = date("Y-m-d");
	$activity = "Add New Worker - " . $first_name . " " . $last_name . " - " . $position_name;

	$sql =  "SELECT first_name, last_name, middle_name FROM workers WHERE first_name = '$first_name' AND last_name = '$last_name' AND middle_name = '$middle_name'";
	$result = mysqli_query($conn, $sql);

	// check if staff already exist
	if (!$result->num_rows > 0) {
		$size = $_FILES['profile']['size'];
		$temp = $_FILES['profile']['tmp_name'];
		$type = $_FILES['profile']['type'];
		$image_name = $_FILES['profile']['name'];

		//check if there is a file to be uploaded
		if ($image_name  == "") {

			$_SESSION['select-image'] = "Please Select An Image!";

			//check if the file is an image
		} elseif (($type == "image/jpeg") || ($type == "image/png") || ($type == "image/gif") || ($type == "image/jpg")) {

			move_uploaded_file($temp, "../worker_images/$image_name");

			$sql = "INSERT INTO workers (emp_id, last_name, first_name, middle_name, birthday, age, gender, address, contact, civil_status, position_id, position, rate, hours_per_day, profile, status, assigned, date_added) VALUES ('$emp_id', '$last_name', '$first_name', '$middle_name', '$bday', '$age', '$gender', '$address', '$contact', '$civil_status', '$position_id', '$position_name', '$rate', '$hours_per_day', '$image_name', '$status', '$assigned', '$date_added')";
			$result = mysqli_query($conn, $sql);

			//check if insert result is true
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

				$_SESSION['add-workers'] = "Worker Added Successfully!";
				header("Location: manage-workers.php");

				//clear texboxes if the result is true
				$_POST['last_name'] = "";
				$_POST['first_name'] = "";
				$_POST['middle_name'] = "";
				$_POST['bday'] = "";
				$_POST['gender'] = "";
				$_POST['address'] = "";
				$_POST['contact'] = "";
				$_POST['civil_status'] = "";
				$_POST['position_id'] = "";
				$_POST['position_name'] = "";
				$_POST['rate'] = "";
			} else {

				$_SESSION['failed-to-add'] = "Failed to Add Worker.";
			}
		} else {

			$_SESSION['invalid-image-format'] = "Invalid Image Format!";
		}
	} else {
		$_SESSION['worker-already-exist'] = "Worker Already Exist.";
	}
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Add Workers</title>

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

	<!-- used to prevent data to insert again when page is refreshed-->
	<script>
		if (window.history.replaceState) {
			window.history.replaceState(null, null, window.location.href);
		}
	</script>

</head>

<body class="app">

	<?php $page = 'worker';
	include('navbar.php'); ?>

	<div class="app-wrapper">

		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item"><a href="manage-workers.php">Manage Workers</a></li>
					<li class="breadcrumb-item active">Add Workers</li>
				</ol>
				<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-user"></i></span> Add Workers</h1>
				<hr class="mb-4">
				<!-- alert messages -->
				<?php
				if (isset($_SESSION['select-image'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['select-image']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['select-image']);
				}
				if (isset($_SESSION['invalid-image-format'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['invalid-image-format']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['invalid-image-format']);
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
				if (isset($_SESSION['worker-already-exist'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['worker-already-exist']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['worker-already-exist']);
				}
				?>
				<div class="row g-4 settings-section">
					<div class="col-12 col-md-12">
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<form class="settings-form" method="post" enctype="multipart/form-data">
									<div class="pb-5">
										<a href="manage-workers.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-list"></i> Worker List</a>
									</div>
									<div class="mb-3">
										<label for="setting-input-2" class="form-label">Last Name: </label>
										<input type="text" name="last_name" class="form-control" id="setting-input-2" autocomplete="off" required value="<?php echo $_POST['last_name']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-2" class="form-label">First Name: </label>
										<input type="text" name="first_name" class="form-control" id="setting-input-2" autocomplete="off" required value="<?php echo $_POST['first_name']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Middle Name:</label>
										<input type="text" name="middle_name" class="form-control" id="setting-input-3" autocomplete="off" placeholder="(Optional)" value="<?php echo $_POST['middle_name']; ?>">
									</div>
									<div class=" mb-3">
										<label for="setting-input-3" class="form-label">Birthday: </label>
										<input type="text" id="date" name="bday" class="form-control" id="setting-input-3" autocomplete="off" placeholder="yyyy-mm-dd" required value="<?php echo $_POST['birthday']; ?>">
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Gender: </label>
											<select name="gender" class="form-select" id="basicSelect" required>
												<option disabled selected>-- Select Gender --</option>
												<option value="Male">Male</option>
												<option value="Female">Female</option>
											</select>
										</fieldset>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Address: </label>
										<input type="text" name="address" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['address']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Contact Number: </label>
										<input type="number" name="contact" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['contact']; ?>">
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Civil Status: </label>
											<select name="civil_status" class="form-select" id="basicSelect" required>
												<option disabled selected>-- Select Civil Status --</option>
												<option value="Single">Single</option>
												<option value="Married">Married</option>
												<option value="Divorced">Divorced</option>
												<option value="Separated">Separated</option>
												<option value="Widowed">Widowed</option>
											</select>
										</fieldset>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Position: </label>
											<select id="id" name="position_id" class="form-select" onchange='fetch_select(this.value)' required>
												<option disabled selected>-- Choose Position -- </option>
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
									<input id="position" type="hidden" name="position_name" class="form-control" required readonly>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Rate Per Day:</label>
										<input id="rate" type="text" name="rate" class="form-control" id="setting-input-3" autocomplete="off" placeholder="₱ 0.00" required readonly>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">No. of Working Hours Per Day:</label>
										<input id="rate" type="text" name="hours" class="form-control" id="setting-input-3" autocomplete="off" value="<?php echo $_POST['hours']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Image Preview : </label><br>
										<img src="assets/images/placeholder-image.png" id="preimage" style="width:120px;height:120px; border:1px solid">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Profile Picture: </label>
										<input type="file" name="profile" class="form-control" id="image" autocomplete="off" onchange="loadfile(event)" required value="<?php echo $_POST['profile']; ?>">
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


	<!-- Javascript -->
	<script src="assets/plugins/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

	<!-- Page Specific JS -->
	<script src="assets/js/app.js"></script>

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