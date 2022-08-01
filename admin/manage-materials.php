<?php

include('../connections/config.php');

$page = 'materials';

error_reporting(0);

//check if the user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

$id = 0;
$edit = false;

//check if add button is clicked 
if (isset($_POST['submit'])) {

	$supplier = mysqli_real_escape_string($conn, $_POST['supplier']);
	$material = mysqli_real_escape_string($conn, $_POST['material']);
	$description = mysqli_real_escape_string($conn, $_POST['description']);
	$unit = mysqli_real_escape_string($conn, $_POST['unit']);
	$price = mysqli_real_escape_string($conn, $_POST['price']);
	$activity = "Add New Material - " . $material . " from " . $supplier;

	$sql =  "SELECT supplier, name, description, unit FROM materials WHERE category_id = '$category' AND category_name = '$category_name' AND supplier = '$supplier' AND name = '$material' AND description = '$description' AND unit = '$unit'";
	$result = mysqli_query($conn, $sql);

	//check if supplier already exist
	if (!$result->num_rows > 0) {

		$sql = "INSERT INTO materials (supplier, name, description, unit, price) VALUES ('$supplier','$material','$description','$unit','$price')";
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

			$_SESSION['add-materials'] = "Material Added Successfully!";

			//clear texboxes if the result is true
			$_POST['category'] = "";
			$_POST['supplier'] = "";
			$_POST['material'] = "";
			$_POST['description'] = "";
			$_POST['unit'] = "";
			$_POST['price'] = "";
		} else {

			$_SESSION['failed-to-add'] = "Failed to Add Material.";
		}
	} else {
		$_SESSION['material-already-exist'] = "Material Already Exist.";
	}
}

//edit button
if (isset($_GET['ID'])) {

	$material_id = $_GET['ID'];
	$edit = true;

	$sql = "SELECT * FROM materials WHERE id = $material_id;";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	if ($result == TRUE) {

		$count = mysqli_num_rows($result);

		if ($count == 1) {
			$category_id = $row['category_id'];
			$category_name = $row['category_name'];
			$supplier = $row['supplier'];
		}
	}
}

//check if update button is clicked
if (isset($_POST['update'])) {

	$supplier = mysqli_real_escape_string($conn, $_POST['supplier']);
	$material = mysqli_real_escape_string($conn, $_POST['material']);
	$description = mysqli_real_escape_string($conn, $_POST['material_description']);
	$unit = mysqli_real_escape_string($conn, $_POST['unit']);
	$price = mysqli_real_escape_string($conn, $_POST['price']);
	$activity = "Update Material Details of " . $material;

	$sql = "UPDATE materials SET supplier = '$supplier', name = '$material', description = '$description', unit = '$unit', price = '$price' WHERE id = '$material_id'";
	$result = mysqli_query($conn, $sql);

	//check if update process if true
	if ($result == TRUE) {

		$sql = "SELECT * FROM requirements WHERE material_id = '$material_id'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);

		if ($result == TRUE) {

			$count = mysqli_num_rows($result);

			if ($count == 1) {
				$quantity = $row['quantity'];
			}
		}

		$new_total = $price * $quantity;

		$sql = "UPDATE requirements SET name = '$material', description = '$description', unit = '$unit', price = '$price', total = '$new_total' WHERE material_id = '$material_id'";
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

		// $sql = "SELECT * FROM materials WHERE id = '$id'";
		// $result = mysqli_query($conn, $sql);
		// $row = mysqli_fetch_assoc($result);

		$_SESSION['update-materials'] = "Material Details Updated Successfully!";
		// header("Location: manage-materials.php");
	} else {

		$_SESSION['failed-to-update'] = "Failed to Update Material Details.";
	}
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Manage Materials</title>

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

	<?php $page = 'material';
	include('navbar.php'); ?>

	<div class="app-wrapper">

		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item active">Manage Materials</li>
				</ol>
				<h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Manage Materials</h1>
				<hr class="mb-4">
				<!-- alert messages -->
				<?php
				if (isset($_SESSION['add-materials'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['add-materials']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['add-materials']);
				}
				if (isset($_SESSION['delete-materials'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['delete-materials']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['delete-materials']);
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
				if (isset($_SESSION['material-not-found'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['material-not-found']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['material-not-found']);
				}
				if (isset($_SESSION['update-materials'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['update-materials']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['update-materials']);
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
				if (isset($_SESSION['material-already-exist'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['material-already-exist']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['material-already-exist']);
				}
				?>
				<div class="row g-4 settings-section">
					<div class="col-12 col-md-4">
						<?php if ($edit == false) : ?>
							<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-tools"></i></span> Add Materials</h1>
						<?php else : ?>
							<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-tools"></i></span> Update Material</h1>
						<?php endif ?>
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<?php if ($edit == false) : ?>
									<form class="settings-form" method="post">
										<div class="mb-3">
											<fieldset class="form-group">
												<label for="setting-input-3" class="form-label">Supplier: </label>
												<select name="supplier" class="form-select">
													<option disabled selected>-- Select Supplier --</option>
													<?php
													$sql = "SELECT * FROM suppliers ORDER BY name ASC";
													$result = mysqli_query($conn, $sql);
													$count = mysqli_num_rows($result);

													//check if engineer exists
													if ($count > 0) {
														while ($row = mysqli_fetch_assoc($result)) {
															$name = $row['name'];

													?>
															<option value="<?php echo $name; ?>"><?php echo $name ?></option>
														<?php
														}
													} else {
														?>
														<option value="">No Suppliers Found</option>
													<?php
													}
													?>
												</select>
											</fieldset>
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Material Name: </label>
											<textarea style="height: 4.5em" name="material" class="form-control" autocomplete="off" required cols="3"><?php echo $_POST['material']; ?></textarea>
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Description: </label>
											<textarea style="height: 6em" name="description" class="form-control" autocomplete="off" required cols="3"><?php echo $_POST['description']; ?></textarea>
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Unit: </label>
											<input type="text" name="unit" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $_POST['unit']; ?>">
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label"> Purchase Price: </label>
											<input type="number" name="price" class="form-control" id="setting-input-3" placeholder="₱ 0.00" autocomplete="off" required value="<?php echo $_POST['price']; ?>">
										</div>
										<button type="submit" name="submit" class="btn app-btn-primary">Add</button>
									</form>
								<?php else : ?>
									<form class="settings-form" method="post">
										<?php
										$sql = "SELECT * FROM materials WHERE id = '$material_id'";
										$result = mysqli_query($conn, $sql);
										$row = mysqli_fetch_assoc($result);

										//check if there are material records
										if ($result == TRUE) {

											$count = mysqli_num_rows($result);

											//check if material exist
											if ($count == 1) {
											} else {

												$_SESSION['material-not-found'] = "Material Not Found.";
												header("Location: manage-materials.php");
											}
										}
										?>
										<div class="mb-3">
											<fieldset class="form-group">
												<label for="setting-input-3" class="form-label">Supplier: </label>
												<select name="supplier" class="form-select">
													<option selected value="<?php echo $supplier; ?>"><?php echo $supplier ?></option>
													<?php
													$sql = "SELECT * FROM suppliers ORDER BY name ASC";
													$result = mysqli_query($conn, $sql);
													$count = mysqli_num_rows($result);

													//check if engineer exists
													if ($count > 0) {
														while ($row = mysqli_fetch_assoc($result)) {
															$name = $row['name'];

													?>
															<option value="<?php echo $name; ?>"><?php echo $name ?></option>
														<?php
														}
													} else {
														?>
														<option value="">No Suppliers Found</option>
													<?php
													}
													?>
												</select>
											</fieldset>
										</div>

										<?php
										$sql = "SELECT * FROM materials WHERE id = '$material_id'";
										$result = mysqli_query($conn, $sql);
										$row = mysqli_fetch_assoc($result);

										//check if there are material records
										if ($result == TRUE) {

											$count = mysqli_num_rows($result);

											//check if material exist
											if ($count == 1) {
											} else {

												$_SESSION['material-not-found'] = "Material Not Found.";
												header("Location: manage-materials.php");
											}
										}
										?>

										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Material Name: </label>
											<textarea style="height: 4.5em" name="material" class="form-control" autocomplete="off" required><?php echo $row['name']; ?></textarea>
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Description: </label>
											<textarea style="height: 6em" name="material_description" class="form-control" autocomplete="off" required><?php echo $row['description']; ?></textarea>
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Unit: </label>
											<input type="text" name="unit" class="form-control" id="setting-input-3" autocomplete="off" required value="<?php echo $row['unit']; ?>">
										</div>
										<div class="mb-3">
											<label for="setting-input-3" class="form-label">Purchase Price: </label>
											<input type="number" name="price" class="form-control" id="setting-input-3" placeholder="₱ 0.00" autocomplete="off" required value="<?php echo $row['price']; ?>">
										</div>
										<button type="submit" name="update" class="btn app-btn-primary">Update</button>
										<a href="manage-materials.php" class="btn app-btn-primary" style="float:right;background-color:grey">Cancel</a>
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
													<th class="cell">Material</th>
													<th class="cell">Stocks</th>
													<th class="cell">Actions</th>
													<th class="cell"></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$sql = "SELECT * FROM materials ORDER BY id DESC";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												//check if materials exist
												if ($count > 0) {

													while ($row = mysqli_fetch_assoc($result)) {
														$id = $row['id'];
														$supplier = $row['supplier'];
														$name = $row['name'];
														$description = $row['description'];
														$unit = $row['unit'];
														$price = $row['price'];
														$stocks = $row['stocks'];
												?>
														<tr>
															<td class="cell" style="padding-top: 0.5em">
																<p>Name: <b><?php echo $name; ?></b></p>
																<p><small>Description: <b><?php echo $description; ?></b></small></p>
																<p><small>Unit: <b><?php echo $unit; ?></b></small></p>
																<p><small>Supplier: <b><?php echo $supplier; ?></b></small></p>
																<p><small>Purchase Price: <b>₱<?php echo number_format($row['price'], 2, '.', ','); ?></b></small></p>
															</td>
															<?php
															if ($stocks == 0) {
															?>
																<td class="cell" style="padding-top: 0.5em; font-weight:bold; color:red">Out Of Stocks</td>
															<?php
															} else {
															?>
																<td class="cell" style="padding-top: 0.5em"><?php echo $stocks; ?></td>
															<?php
															}
															?>
															<td><a href="manage-materials.php?ID=<?php echo $id; ?>" class="btn app-btn btn-info" style="color:white"><i class="fa fa-edit"></i> Edit</a></td>
															<td><a href="delete-materials.php?ID=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to remove this material record?');" class="btn app-btn btn-danger" style="color:white"><i class="fa fa-trash"></i> Delete</a></td>
														</tr>
												<?php
													}
												} else {
													echo "<script>alert('No Materials Found!')</script>";
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

	<!-- fetch supplier details script -->
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
					$('#name').val((data[0].name));
				}

			});
		}
	</script>

	<script>
		$(document).ready(function() {
			$('#category').change(function() {
				var category_id = $(this).val();
				$.ajax({
					url: "fetch-supplier.php",
					method: "POST",
					data: {
						categoryId: category_id
					},
					dataType: "text",
					success: function(data) {
						$('#supplier').html(data);
					}
				});
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