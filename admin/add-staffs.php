<?php

include('../connections/config.php');

error_reporting(0);

//check if the user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

//check if the add button is clicked
if (isset($_POST['submit'])) {

	$staff_id = "STF-" . rand(000, 999);
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
	$position = mysqli_real_escape_string($conn, $_POST['position']);
	$status = "Active";
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$password = mysqli_real_escape_string($conn, md5($_POST['password']));
	$confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));
	$otp = 0;
	$access = mysqli_real_escape_string($conn, $_POST['access']);
	$date_added = date("Y-m-d");
	$last_login = "0000-00-00 00:00:00";
	$last_activity = "0000-00-00 00:00:00";
	$last_logout = "0000-00-00 00:00:00";
	$activity = "Add New " . $access . " - " . $first_name . " " . $last_name;

	//check if username uses alphanumeric characters
	if (preg_match("/^[a-zA-Z0-9_]*$/", $username)) {

		//check if password uses alphanumeric characters
		if (ctype_alnum($password)) {

			//check if password is 8 or more characters long
			if (strlen($password) >= 8) {

				//check if both inputed password are the same
				if ($password == $confirm_password) {

					$sql =  "SELECT last_name, first_name, middle_name FROM staffs WHERE last_name = '$last_name'  AND first_name = '$first_name' AND middle_name = '$middle_name'";
					$result = mysqli_query($conn, $sql);

					// check if staff already exist
					if (!$result->num_rows > 0) {

						$sql =  "SELECT email_address FROM staffs WHERE email_address = '$address'";
						$result = mysqli_query($conn, $sql);

						//check if email already exist
						if (!$result->num_rows > 0) {

							$sql =  "SELECT user_name FROM staffs WHERE user_name = '$username'";
							$result = mysqli_query($conn, $sql);

							//check if username already exist
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

									move_uploaded_file($temp, "../staff_images/$image_name");

									$sql = "INSERT INTO staffs (staff_id, last_name, first_name, middle_name, birthday, age, gender, address, contact, civil_status, profile, status, email, username, password, otp, access, date_added, last_login, last_activity, last_logout) VALUES ('$staff_id', '$last_name', '$first_name', '$middle_name', '$bday', '$age', '$gender', '$address', '$contact', '$civil_status', '$image_name', '$status', '$email', '$username', '$password', $otp, '$access', '$date_added', '$last_login', '$last_activity','$last_logout')";
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

										$_SESSION['add-staffs'] = "Staff Added Successfully!";
										header("Location: manage-staffs.php");

										//clear textboxes if the result is true
										$_POST['last_name'] = "";
										$_POST['first_name'] = "";
										$_POST['middle_name'] = "";
										$_POST['bday'] = "";
										$_POST['gender'] = "";
										$_POST['address'] = "";
										$_POST['contact'] = "";
										$_POST['civil_status'] = "";
										$_POST['position'] = "";
										$_POST['email'] = "";
										$_POST['username'] = "";
										$_POST['password'] = "";
										$_POST['confirm_password'] = "";
										$_POST['access'] = "";
									} else {
										$_SESSION['failed-to-add'] = "Failed to Add Staff.";
									}
								} else {
									$_SESSION['invalid-image-format'] = "Invalid Image Format!";
								}
							} else {
								$_SESSION['username-already-exist'] = "Username Already Taken! Please Try Another Username.";
							}
						} else {
							$_SESSION['email-already-exist'] = "Email Address Already Used! Please Try Another Email Address.";
						}
					} else {
						$_SESSION['staff-already-exist'] = "Staff Already Exist.";
					}
				} else {
					$_SESSION['password-not-matched'] = "Password Not Matched.";
				}
			} else {
				$_SESSION['invalid-password'] = "Invalid Password! Password Must Be Aleast 8 Characters Long And Must Consists Of An Uppercase Letter, A Lowercase Letter And A Number.";
			}
		} else {
			$_SESSION['invalid-password'] = "Invalid Password! Password Must Be Aleast 8 Characters Long And Must Consists Of An Uppercase Letter, A Lowercase Letter And A Number.";
		}
	} else {
		$_SESSION['invalid-username'] = "Invalid Username! Space And Other Special Characters Are Not Allowed, Only Underscore( _ ).";
	}
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Add Staffs</title>

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

	<?php $page = 'staff';
	include('navbar.php'); ?>

	<div class="app-wrapper">
		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item"><a href="manage-staffs.php">Manage Staffs</a></li>
					<li class="breadcrumb-item active">Add Staffs</li>
				</ol>
				<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-user"></i></span> Add Staffs</h1>
				<hr class="mb-4">
				<!-- alert messages -->
				<?php
				if (isset($_SESSION['select-image'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['select-image']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['select-image']);
				}
				if (isset($_SESSION['invalid-image-format'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
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
				if (isset($_SESSION['password-not-matched'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['password-not-matched']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['password-not-matched']);
				}
				if (isset($_SESSION['staff-already-exist'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['staff-already-exist']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['staff-already-exist']);
				}
				if (isset($_SESSION['email-already-exist'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['email-already-exist']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['email-already-exist']);
				}
				if (isset($_SESSION['username-already-exist'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['username-already-exist']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['username-already-exist']);
				}
				if (isset($_SESSION['invalid-password'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['invalid-password']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['invalid-password']);
				}
				if (isset($_SESSION['invalid-username'])) {
				?>
					<div class="alert alert-warning alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['invalid-username']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['invalid-username']);
				}
				?>
				<div class="row g-4 settings-section">
					<div class="col-12 col-md-12">
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<form class="settings-form" method="post" enctype="multipart/form-data">
									<div class="pb-5">
										<a href="manage-staffs.php" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-list"></i> Staff List</a>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Last Name: </label>
										<input type="text" name="last_name" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['last_name']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">First Name: </label>
										<input type="text" name="first_name" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['first_name']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Middle Name: </label>
										<input type="text" name="middle_name" class="form-control" id="setting-input-3" autocomplete="off" placeholder="(Optional)" value="<?php echo $_POST['middle_name']; ?>">
									</div>
									<div class="mb-3">
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
										<label for="setting-input-3" class="form-label">Position: </label>
										<input type="text" name="position" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['position']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Image Preview : </label><br>
										<img src="assets/images/placeholder-image.png" id="preimage" style="width:120px;height:120px; border:1px solid">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Profile Picture: </label>
										<input type="file" name="profile" class="form-control" id="image" onchange="loadfile(event)" autocomplete="off" required value="<?php echo $_POST['profile']; ?>">
									</div>
									<div class="mb-3">
										<br>
										<label for="setting-input-3" class="form-label" style="color:#5b99ea">New User Account: </label>
									</div>
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">User Type: </label>
											<select name="access" class="form-select" id="basicSelect" required>
												<option disabled selected>-- Select User Type --</option>
												<option value="Admin">Admin</option>
												<option value="Staff">Staff</option>
											</select>
										</fieldset>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Email: </label>
										<input type="email" name="email" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['email']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Username: </label>
										<input type="text" name="username" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['username']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Password: </label>
										<input type="password" name="password" class="form-control" id="setting-input-3" autocomplete="off" placeholder="Password Must Be Aleast 8 Characters Long And Must Consists Of An Uppercase Letter, A Lowercase Letter And A Number" required value="<?php echo $_POST['password']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Confirm Password: </label>
										<input type="password" name="confirm_password" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['confirm_password']; ?>">
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

<!-- <script>
	setTimeout(function() {
		document.getElementById("alert").style.display = "none";
	}, 3000);
</script> -->

</html>