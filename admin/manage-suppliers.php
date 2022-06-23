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

	$category =  mysqli_real_escape_string($conn, $_POST['category']);
	$category_name =  mysqli_real_escape_string($conn, $_POST['category_name']);
	$name =  mysqli_real_escape_string($conn, $_POST['name']);
	$person = mysqli_real_escape_string($conn, $_POST['person']);
	$contact = mysqli_real_escape_string($conn, $_POST['contact']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$address = mysqli_real_escape_string($conn, $_POST['address']);
	$status = "Active";
	$activity = "Add New " . $category_name . " Supplier - " . $name;

	$sql =  "SELECT category_id, name FROM suppliers WHERE category_id = '$category' AND name = '$name'";
	$result = mysqli_query($conn, $sql);

	//check if supplier already exist
	if (!$result->num_rows > 0) {

		$sql = "INSERT INTO suppliers (category_id, category_name, name, person, contact, email, address, status) VALUES ('$category', '$category_name', '$name', '$person', '$contact', '$email', '$address', '$status')";
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

			$_SESSION['add-suppliers'] = "Supplier Added Successfully!";

			//clear texboxes if the result is true
			$_POST['category'] = "";
			$_POST['name'] = "";
			$_POST['person'] = "";
			$_POST['contact'] = "";
			$_POST['email'] = "";
			$_POST['address'] = "";
		} else {

			$_SESSION['failed-to-add'] = "Failed to Add Supplier.";
		}
	} else {
		$_SESSION['supplier-already-exist'] = "Supplier Already Exist.";
	}
}

//edit button
if (isset($_GET['ID'])) {

	$supplier_id = $_GET['ID'];
	$edit = true;

	$sql = "SELECT * FROM suppliers WHERE id = $supplier_id;";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	if ($result == TRUE) {

		$count = mysqli_num_rows($result);

		if ($count == 1) {
			$category_id = $row['category_id'];
			$category_name = $row['category_name'];
		}
	}
}

//check if update button is clicked
if (isset($_POST['update'])) {

	$category = mysqli_real_escape_string($conn, $_POST['category']);
	$category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$person = mysqli_real_escape_string($conn, $_POST['person']);
	$contact = mysqli_real_escape_string($conn, $_POST['contact']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$address = mysqli_real_escape_string($conn, $_POST['address']);
	$status = mysqli_real_escape_string($conn, $_POST['status']);
	$activity = "Update Supplier Details of " . $name;

	$sql = "UPDATE suppliers SET category_id = '$category', category_name = '$category_name', name = '$name', person = '$person', contact = '$contact', email = '$email', address = '$address', status = '$status' WHERE id = '$supplier_id'";
	$result = mysqli_query($conn, $sql);

	//check if update process if true
	if ($result == TRUE) {

		// $sql = "UPDATE materials SET name = '$name' WHERE name = '$name'";
		// $result = mysqli_query($conn, $sql);

		// //update last activity date and time
		// if ($result = TRUE) {
		// } else {
		// 	echo "<script>alert('Error in Updating Last Activity')</script>";
		// }

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
		$_SESSION['update-suppliers'] = "Supplier Details Updated Successfully!";
		// header("Location: manage-suppliers.php");
	} else {

		$_SESSION['failed-to-update'] = "Failed to Update Supplier Details.";
	}
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Manage Suppliers</title>

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

	<?php $page = 'supplier';
	include('navbar.php'); ?>

	<div class="app-wrapper">

		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item active">Manage Suppliers</li>
				</ol>
				<h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Manage Suppliers</h1>
				<a href="manage-categories.php" class="btn app-btn btn-secondary" style="color:white"><i class="fas fa-layer-group"></i> Manage Categories</a>
				<a href="print-suppliers.php" target="_blank" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-print"></i> Print List</a>
				<hr class="mb-4">
				<!-- alert messages -->
				<?php
				if (isset($_SESSION['add-suppliers'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['add-suppliers']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['add-suppliers']);
				}
				if (isset($_SESSION['delete-suppliers'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['delete-suppliers']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['delete-suppliers']);
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
				if (isset($_SESSION['supplier-not-found'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['supplier-not-found']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['supplier-not-found']);
				}
				if (isset($_SESSION['update-suppliers'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['update-suppliers']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['update-suppliers']);
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
				if (isset($_SESSION['supplier-already-exist'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['supplier-already-exist']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['supplier-already-exist']);
				}
				?>
				<div class="row g-4 settings-section">
					<div class="col-12 col-md-4">
						<?php if ($edit == false) : ?>
							<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-store"></i></span> Add Suppliers</h1>
						<?php else : ?>
							<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-store"></i></span> Update Supplier</h1>
						<?php endif ?>
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<?php if ($edit == false) : ?>
									<form class="settings-form" method="post">
										<div class="mb-3">
											<fieldset class="form-group">
												<label for="setting-input-3" class="form-label">Category: </label>
												<select name="category" class="form-select" id="category" onchange='fetch_select(this.value)' required>
													<option disabled selected>-- Select Supplier Category --</option>
													<?php
													$sql = "SELECT * FROM categories";
													$result = mysqli_query($conn, $sql);
													$count = mysqli_num_rows($result);

													//check if client exists
													if ($count > 0) {
														while ($row = mysqli_fetch_assoc($result)) {
															$id = $row['id'];
															$name = $row['name'];
													?>
															<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
														<?php
														}
													} else {
														?>
														<option value="">No Category Found</option>
													<?php
													}
													?>
												</select>
											</fieldset>
										</div>
										<input id="category_name" type="hidden" name="category_name" class="form-control" required readonly>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Name: </label>
											<textarea style="height: 4.5em" name="name" class="form-control" autocomplete="off" required cols="3"><?php echo $_POST['name']; ?></textarea>
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Contact Person: </label>
											<input type="text" name="person" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['person']; ?>">
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Contact No.: </label>
											<input type="number" name="contact" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['contact']; ?>">
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Email: </label>
											<input type="email" name="email" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['email']; ?>">
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Address: </label>
											<textarea style="height: 6em" name="address" class="form-control" autocomplete="off" required cols="3"><?php echo $_POST['address']; ?></textarea>
										</div>
										<button type="submit" name="submit" class="btn app-btn-primary">Add</button>
									</form>
								<?php else : ?>
									<form class="settings-form" method="post">
										<div class="mb-3">
											<fieldset class="form-group">
												<label for="setting-input-3" class="form-label">Supplier Category: </label>
												<select name="category" class="form-select" id="category" onchange='fetch_select(this.value)' required>
													<option value="<?php echo $category_id; ?>"><?php echo $category_name ?></option>

													<?php
													$sql = "SELECT * FROM categories";
													$result = mysqli_query($conn, $sql);
													$count = mysqli_num_rows($result);

													//check if client exists
													if ($count > 0) {
														while ($row = mysqli_fetch_assoc($result)) {
															$category_id = $row['id'];
															$name = $row['name'];
													?>
															<option value="<?php echo $category_id; ?>"><?php echo $name; ?></option>
														<?php
														}
													} else {
														?>
														<option value="">No Category Found</option>
													<?php
													}
													?>

												</select>
											</fieldset>
										</div>

										<?php
										$id = $_GET['ID'];

										$sql = "SELECT * FROM suppliers WHERE id = '$id'";
										$result = mysqli_query($conn, $sql);
										$row = mysqli_fetch_assoc($result);

										//check if there are supplier records
										if ($result == TRUE) {

											$count = mysqli_num_rows($result);

											//check if supplier exist
											if ($count == 1) {
											} else {

												$_SESSION['supplier-not-found'] = "Supplier Not Found.";
												header("Location: manage-suppliers.php");
											}
										}
										?>
										<input id="category_name" type="hidden" name="category_name" class="form-control" value="<?php echo $row['category_name']; ?>" required readonly>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Name: </label>
											<textarea style="height: 4.5em" name="name" class="form-control" autocomplete="off" required cols="3"><?php echo $row['name']; ?></textarea>
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Contact Person: </label>
											<input type="text" name="person" class="form-control" id="setting-input-3" value="<?php echo $row['person']; ?>" autocomplete="off" required>
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Contact No.: </label>
											<input type="number" name="contact" class="form-control" id="setting-input-3" value="<?php echo $row['contact']; ?>" autocomplete="off" required>
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Contact No.: </label>
											<input type="email" name="email" class="form-control" id="setting-input-3" value="<?php echo $row['email']; ?>" autocomplete="off" required>
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Address: </label>
											<textarea style="height: 6em" name="address" class="form-control" autocomplete="off" required cols="3"><?php echo $row['address']; ?></textarea>
										</div>
										<div class="mb-3">
											<fieldset class="form-group">
												<label for="setting-input-3" class="form-label">Status: </label>
												<select name="status" class="form-select" id="basicSelect">
													<option value="Active" <?php echo ($row['status'] == "Active") ? 'selected' : ''; ?>>Active</option>
													<option value="Inactive" <?php echo ($row['status'] == "Inactive") ? 'selected' : ''; ?>>Inactive</option>
												</select>
											</fieldset>
										</div>
										<button type="submit" name="update" class="btn app-btn-primary">Update</button>
										<a href="manage-suppliers.php" class="btn app-btn-primary" style="float:right;background-color:grey">Cancel</a>
									</form>
								<?php endif ?>
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
													<th class="cell">Supplier</th>
													<th class="cell">Status</th>
													<th class="cell">Actions</th>
													<th class="cell"></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$sql = "SELECT * FROM suppliers";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												//check if suppliers exist
												if ($count > 0) {

													while ($row = mysqli_fetch_assoc($result)) {
														$id = $row['id'];
														$name = $row['name'];
														$category_name = $row['category_name'];
														$person = $row['person'];
														$email = $row['email'];
														$contact = $row['contact'];
														$address = $row['address'];
														$status = $row['status'];
												?>
														<tr>
															<td class="cell" style="padding-top: 0.5em">
																<p>Name: <b><?php echo $name; ?></b></p>
																<p><small>Category: <b><?php echo $category_name; ?></b></small></p>
																<p><small>Contact Person: <b><?php echo $person; ?></b></small></p>
																<p><small>Contact No.: <b><?php echo $contact; ?></b></small></p>
																<p><small>Email: <b><?php echo $email; ?></b></small></p>
																<p><small>Address: <b><?php echo $address; ?></b></small></p>
															</td>
															<?php
															if ($row['status'] == "Active") {
															?>
																<td class="cell" style="padding-top: 0.5em; font-weight:bold; color:green">Active</td>
															<?php
															} else if ($row['status'] == "Inactive") {
															?>
																<td class="cell" style="padding-top: 0.5em; font-weight:bold; color:red">Inactive</td>
															<?php
															}
															?>
															<td><a href="manage-suppliers.php?ID=<?php echo $id; ?>" class="btn app-btn btn-info" style="color:white"><i class="fa fa-edit"></i> Edit</a></td>
															<td><a href="delete-suppliers.php?ID=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to remove this supplier record?');" class="btn app-btn btn-danger" style="color:white"><i class="fa fa-trash"></i> Delete</a></td>
														</tr>
												<?php
													}
												} else {
													echo "<script>alert('No Suppliers Found!')</script>";
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

	<!-- fetch category details script -->
	<script>
		function fetch_select(val) {
			$.ajax({
				url: "fetch-categories.php",
				type: "POST",
				data: {
					"get_option": val
				},
				dataType: "JSON",
				success: function(data) {
					$('#category_name').val((data[0].name));
				}

			});
		}
	</script>

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