<?php

include('../connections/config.php');

error_reporting(0);

//check if user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

$id = 0;
$edit = false;

//check if add button is clicked 
if (isset($_POST['submit'])) {

	$clientname =  mysqli_real_escape_string($conn, $_POST['name']);
	$companyname =  mysqli_real_escape_string($conn, $_POST['companyname']);
	$contact = mysqli_real_escape_string($conn, $_POST['contact']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$address = mysqli_real_escape_string($conn, $_POST['address']);
	$status = "1";
	$activity = "Add New Client - " . $clientname;

	$sql =  "SELECT name FROM clients WHERE name = '$clientname' OR company_name = '$companyname'";
	$result = mysqli_query($conn, $sql);

	//check if client already exist
	if (!$result->num_rows > 0) {

		$sql = "INSERT INTO clients (name, company_name, contact, email, address, status) VALUES ('$clientname', '$companyname', '$contact', '$email', '$address', '$status')";
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

			$_SESSION['add-clients'] = "Client Added Successfully!";

			//clear texboxes if the result is true
			$_POST['name'] = "";
			$_POST['company_name'] = "";
			$_POST['contact'] = "";
			$_POST['email'] = "";
			$_POST['address'] = "";
		} else {

			$_SESSION['failed-to-add'] = "Failed to Add Client.";
		}
	} else {
		$_SESSION['client-already-exist'] = "Client Already Exist.";
	}
}

//edit button
if (isset($_GET['ID'])) {

	$client_id = $_GET['ID'];
	$edit = true;

	$sql = "SELECT * FROM clients WHERE id = $client_id;";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	if ($result == TRUE) {

		$count = mysqli_num_rows($result);

		if ($count == 1) {
			$clientname = $row['name'];
			$companyname = $row['company_name'];
			$contact = $row['contact'];
			$email = $row['email'];
			$address = $row['address'];
			$status = $row['status'];
		}
	}
}

//check if update button is clicked
if (isset($_POST['update'])) {

	$clientname =  mysqli_real_escape_string($conn, $_POST['name']);
	$companyname =  mysqli_real_escape_string($conn, $_POST['companyname']);
	$contact = mysqli_real_escape_string($conn, $_POST['contact']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$address = mysqli_real_escape_string($conn, $_POST['address']);
	$status = mysqli_real_escape_string($conn, $_POST['status']);
	$activity = "Update Client Details of " . $clientname;

	$sql = "UPDATE clients SET name = '$clientname', company_name = '$companyname', contact = '$contact', email = '$email', address = '$address', status = '$status' WHERE id = $client_id";
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

		$_SESSION['update-clients'] = "Client Details Updated Successfully!";
		// header("Location: manage-clients.php?ID=$client_id");
	} else {

		$_SESSION['failed-to-update'] = "Failed to Update Client Details.";
	}
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Manage Clients</title>

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

	<?php $page = 'client';
	include('navbar.php'); ?>

	<div class="app-wrapper">

		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item active">Manage Clients</li>
				</ol>
				<h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Manage Clients</h1>
				<a href="print-clients.php" target="_blank" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-print"></i> Print List</a>
				<hr class="mb-4">
				<!-- alert messages -->
				<?php
				if (isset($_SESSION['add-clients'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['add-clients']; ?> </strong>
					</div>
				<?php
					unset($_SESSION['add-clients']);
				}
				if (isset($_SESSION['client-already-exist'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['client-already-exist']; ?> </strong>
					</div>
				<?php
					unset($_SESSION['client-already-exist']);
				}
				if (isset($_SESSION['delete-clients'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['delete-clients']; ?> </strong>
					</div>
				<?php
					unset($_SESSION['delete-clients']);
				}
				if (isset($_SESSION['failed-to-delete'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['failed-to-delete']; ?> </strong>
					</div>
				<?php
					unset($_SESSION['failed-to-delete']);
				}
				if (isset($_SESSION['client-not-found'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['client-not-found']; ?> </strong>
					</div>
				<?php
					unset($_SESSION['client-not-found']);
				}
				if (isset($_SESSION['update-clients'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['update-clients']; ?> </strong>
					</div>
				<?php
					unset($_SESSION['update-clients']);
				}
				if (isset($_SESSION['failed-to-add'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['failed-to-add']; ?> </strong>
					</div>
				<?php
					unset($_SESSION['failed-to-add']);
				}
				if (isset($_SESSION['failed-to-update'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['failed-to-update']; ?> </strong>
					</div>
				<?php
					unset($_SESSION['failed-to-update']);
				}
				?>
				<div class="row g-4 settings-section">
					<div class="col-12 col-md-4">
						<?php if ($edit == true) : ?>
							<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-user"></i></span> Update Client</h1>
						<?php else : ?>
							<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-users"></i></span> Add Clients</h1>
						<?php endif ?>
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<form class="settings-form" method="post">
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Full Name: </label>
										<input type="text" name="name" class="form-control" <?php if ($edit == true) : ?> value="<?php echo $clientname; ?>" <?php else : ?> value="<?php echo $_POST['name']; ?>" <?php endif ?> autocomplete="off" required>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Company Name: </label>
										<input type="text" name="companyname" class="form-control" <?php if ($edit == true) : ?> value="<?php echo $companyname; ?>" <?php else : ?> value="<?php echo $_POST['companyname']; ?>" <?php endif ?> autocomplete="off" required>
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Contact No.: </label>
										<input type="number" name="contact" class="form-control" <?php if ($edit == true) : ?> value="<?php echo $contact; ?>" <?php endif ?> autocomplete="off" required value="<?php echo $_POST['contact']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Email: </label>
										<input type="email" name="email" class="form-control" <?php if ($edit == true) : ?> value="<?php echo $email; ?>" <?php endif ?> autocomplete="off" required value="<?php echo $_POST['email']; ?>">
									</div>
									<div class="mb-3">
										<label for="setting-input-3" class="form-label">Address: </label>
										<textarea style="height: 6em" name="address" class="form-control" autocomplete="off" required cols="3"><?php if ($edit == true) : ?><?php echo $address; ?><?php else : ?><?php echo $_POST['address']; ?><?php endif ?></textarea>
									</div>
									<?php if ($edit == true) : ?>
										<div class="mb-3">
											<fieldset class="form-group">
												<label for="setting-input-3" class="form-label">Status: </label>
												<select name="status" class="form-select" id="basicSelect" required>
													<option value="1" <?php echo ($row['status'] == "1") ? 'selected' : ''; ?>>Active</option>
													<option value="0" <?php echo ($row['status'] == "0") ? 'selected' : ''; ?>>Inactive</option>
												</select>
											</fieldset>
										</div>
									<?php endif ?>
									<?php if ($edit == true) : ?>
										<button type="submit" name="update" class="btn app-btn-primary">Update</button>
										<a href="manage-clients.php" class="btn app-btn-primary" style="float:right;background-color:grey">Cancel</a>
									<?php else : ?>
										<button type="submit" name="submit" class="btn app-btn-primary">Add</button>
									<?php endif ?>
								</form>
							</div>
							<!--//app-card-body-->
						</div>
						<!--//app-card-->
					</div>
					<div class="col-12 col-md-8">
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<form class="settings-form">
									<div class="mb-3">
										<table id="myTable" class="table app-table-hover mb-0 text-left">
											<thead>
												<tr>
													<th class="cell">Client</th>
													<th class="cell">Actions</th>
													<th class="cell"></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$sql = "SELECT * FROM clients";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												//check if clients exist
												if ($count > 0) {

													while ($row = mysqli_fetch_assoc($result)) {
														$id = $row['id'];
														$clientname = $row['name'];
														$companyname = $row['company_name'];
														$contact = $row['contact'];
														$email = $row['email'];
														$address = $row['address'];
														$status = $row['status'];
												?>
														<tr>
															<td class="cell" style="padding-top: 1em">
																<p>Name: <b><?php echo $clientname; ?></b></p>
																<p>Company Name: <b><?php echo $companyname; ?></b></p>
																<p><small>Contact No.: <b><?php echo $contact; ?></b></small></p>
																<p><small>Email: <b><?php echo $email; ?></b></small></p>
																<p><small>Address: <b><?php echo $address; ?></b></small></p>
																<?php if ($status == '1') : ?>
																	<small>Status: </small><b><small style="color: green">Active</p></b>
																<?php else : ?>
																	<small>Status: </small><b><small style="color: red">Inactive</p></b>
																<?php endif; ?>

															</td>

															<td>
																<a href="manage-clients.php?ID=<?php echo $id; ?>" class="btn app-btn btn-info" style="color:white"><i class="fa fa-edit"></i> Edit</a>
															</td>
															<td>
																<a href="delete-clients.php?ID=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to delete this client record?')" class=" btn app-btn btn-danger" style="color:white"><i class="fa fa-trash"></i> Delete</a>
															</td>
														</tr>
												<?php
													}
												} else {
													echo "<script>alert('No Clients Found!')</script>";
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