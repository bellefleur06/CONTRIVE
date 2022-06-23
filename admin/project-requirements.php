<?php

include('../connections/config.php');

if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM projects WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are project records
if ($result == TRUE) {

	$count = mysqli_num_rows($result);

	//check if project exist
	if ($count == 1) {

		$project_id = $row['id'];
		$project_name = $row['name'];
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

$material_id =  "";
$category_id =  "";
$category_name =  "";
$material_name =  "";
$description =  "";
$unit =  "";
$stocks = "";
$quantity = "";
$price = "";
$id = 0;
$update = false;

//add requirement
if (isset($_POST['submit'])) {

	$project_id = mysqli_real_escape_string($conn, $_POST['project_id']);
	$project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
	$material_id =  mysqli_real_escape_string($conn, $_POST['material']);
	$category_id =  mysqli_real_escape_string($conn, $_POST['category_id']);
	$category_name =  mysqli_real_escape_string($conn, $_POST['category_name']);
	$material_name =  mysqli_real_escape_string($conn, $_POST['name']);
	$description =  mysqli_real_escape_string($conn, $_POST['description']);
	$unit =  mysqli_real_escape_string($conn, $_POST['unit']);
	$stocks = mysqli_real_escape_string($conn, $_POST['stocks']);
	$quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
	$price = mysqli_real_escape_string($conn, $_POST['price']);
	$total = $price * $quantity;
	$total_stocks =  $stocks - $quantity;
	$activity = "Add New Project Requirement For " . $project_name . " - " . $quantity . " " . $unit . " " . $material_name;

	//check if there are enough stocks for the requirement quantity
	if ($quantity <= $stocks) {

		$sql = "INSERT INTO requirements (project_id, project, category_id, category_name, material_id, name, description, unit, quantity, price, total) VALUES ('$project_id', '$project_name', '$category_id', '$category_name', '$material_id', '$material_name', '$description', '$unit', '$quantity', '$price', '$total')";
		$result = mysqli_query($conn, $sql);

		//check if insert process if true
		if ($result == TRUE) {

			$_POST['quantity'] = "";

			$sql = "UPDATE materials SET stocks = $total_stocks WHERE id = $material_id";
			$result = mysqli_query($conn, $sql);

			//update materials stock
			if ($result = TRUE) {
			} else {
				echo "<script>alert('Error in Updating Stocks')</script>";
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

			$_SESSION['add-materials'] = "Material Added Successfully!";
		} else {

			$_SESSION['failed-to-add'] = "Failed to Add Material.";
		}
	} else {
		$_SESSION['insufficient-stock'] = "Requirements Quantity Is Larger Than Stocks.";
	}
}

//remove requirement
if (isset($_GET['ID']) && isset($_GET['delete'])) {

	$requirements_id = $_GET['delete'];

	$sql = "SELECT * FROM requirements WHERE id = '$requirements_id'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	//check if there are material records
	if ($result == TRUE) {

		$count = mysqli_num_rows($result);

		//check if material exist
		if ($count == 1) {
		} else {

			$_SESSION['project-not-found'] = "Project Not Found.";
			header("Location: manage-projects.php");
		}
	}

	$project_name = $row['project'];
	$material_name = $row['name'];
	$material_id = $row['material_id'];
	$quantity = $row['quantity'];

	$sql = "SELECT * FROM materials WHERE id = '$material_id'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	//check if there are material records
	if ($result == TRUE) {

		$count = mysqli_num_rows($result);

		//check if material exist
		if ($count == 1) {
		} else {

			$_SESSION['project-not-found'] = "Project Not Found.";
			header("Location: manage-projects.php");
		}
	}

	$stocks = $row['stocks'];
	$unit = $row['unit'];
	$total_stocks = $quantity + $stocks;
	$activity = "Removed Project Requirement For " . $project_name . " - " . $quantity . " " . $unit . " " . $material_name;

	$sql = "DELETE FROM requirements WHERE id = '$requirements_id'";
	$result = mysqli_query($conn, $sql);

	//check if delete process is true
	if ($result == TRUE) {

		$sql = "UPDATE materials SET stocks = $total_stocks WHERE id = $material_id";
		$result = mysqli_query($conn, $sql);

		//update materials stock
		if ($result = TRUE) {
		} else {
			echo "<script>alert('Error in Updating Stocks')</script>";
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

		// $_SESSION['remove-materials'] = "Material Removed Successfully!";
		// header("Location: project-requirements.php?ID=$project_id");
		echo "<script>alert('Material Removed Successfully!');window.location.replace('project-requirements.php?ID=$project_id');</script>";
	} else {

		// $_SESSION['failed-to-remove'] = "Failed To Remove Material.";
		// header("Location: project-requirements.php?ID=$project_id");
		echo "<script>alert('Failed To Remove Material');window.location.replace('project-requirements.php?ID=$project_id');</script>";
	}
}

//update button

if (isset($_GET['ID']) && isset($_GET['id'])) {

	$project_id = $_GET['ID'];
	$requirements_id = $_GET['id'];
	$update = true;

	$sql = "SELECT * FROM requirements WHERE id = '$requirements_id' AND project_id = $project_id";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	if ($result == TRUE) {

		$count = mysqli_num_rows($result);

		if ($count == 1) {
			$requirement_name = $row['name'];
			$quantity = $row['quantity'];
			$material_id = $row['material_id'];
		}
	}

	$sql = "SELECT * FROM materials WHERE id = '$material_id'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	if ($result == TRUE) {

		$count = mysqli_num_rows($result);

		if ($count == 1) {
			$material_stocks = $row['stocks'];
			$material_price = $row['price'];
		}
	}
}

//update requirement
if (isset($_POST['update'])) {

	$requirement_id =  mysqli_real_escape_string($conn, $_POST['requirement_id']);
	$requirement_name =  mysqli_real_escape_string($conn, $_POST['requirement_name']);
	$material_price =  mysqli_real_escape_string($conn, $_POST['material_price']);
	$old_quantity =  mysqli_real_escape_string($conn, $_POST['old_quantity']);
	$quantity =  mysqli_real_escape_string($conn, $_POST['quantity']);

	$new_quantity = $quantity -  $old_quantity;
	$total = $material_price * $new_quantity;

	$material_stocks = mysqli_real_escape_string($conn, $_POST['material_stocks']);
	$total_stocks =  $material_stocks - $new_quantity;

	$activity = "Update Project Material Quantity For " . $project_name . " - " . $new_quantity . " " . $unit . " " . $material_name;

	if ($new_quantity <= $material_stocks) {

		$sql = "UPDATE requirements SET quantity = '$quantity', total = '$total' WHERE id = $requirement_id";
		$result =  mysqli_query($conn, $sql);

		//check if update process if true
		if ($result == TRUE) {

			$sql = "UPDATE materials SET stocks = $total_stocks WHERE id = $material_id";
			$result = mysqli_query($conn, $sql);

			//update materials stock
			if ($result = TRUE) {
			} else {
				echo "<script>alert('Error in Updating Stocks')</script>";
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

			$_SESSION['update-quantity'] = "Material Quantity Updated Successfully!";
			header("Location: project-requirements.php?ID=$project_id");
		} else {

			$_SESSION['failed-to-update'] = "Failed to Update Material Quantity.";
		}
	} else {
		$_SESSION['insufficient-stock'] = "Requirements Quantity Is Larger Than Stocks.";
	}
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Project Requirements</title>

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

	<?php $page = 'project';
	include('navbar.php'); ?>

	<div class="app-wrapper">

		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item active"><a href="manage-projects.php">Manage Projects</a></li>
					<li class="breadcrumb-item active"><a href="project-details.php?ID=<?php echo $project_id; ?>">Project Details</a></li>
					<li class="breadcrumb-item active">Project Requirements</li>
				</ol>
				<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-tools"></i></span> "<?php echo $project_name; ?>" Project Requirements </h1>
				<!-- <a href="#addrequirementModal" class="btn app-btn-primary" data-toggle="modal"><i class="fa fa-plus"></i> Add Requirement</a> -->
				<a href="project-details.php?ID=<?php echo $project_id; ?>" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-arrow-left"></i> Go Back</a>
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
				if (isset($_SESSION['failed-to-add'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['failed-to-add']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['failed-to-add']);
				}
				if (isset($_SESSION['remove-materials'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['remove-materials']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['remove-materials']);
				}
				if (isset($_SESSION['failed-to-remove'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['failed-to-remove']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['failed-to-remove']);
				}
				if (isset($_SESSION['update-quantity'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['update-quantity']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['update-quantity']);
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
				if (isset($_SESSION['insufficient-stock'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['insufficient-stock']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['insufficient-stock']);
				}
				?>
				<div class="row g-4 settings-section">
					<div class="col-12 col-md-4">
						<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-tools"></i></span> Add Project Materials</h1>
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<form class="settings-form" method="post">
									<div class="mb-3">
										<fieldset class="form-group">
											<label for="setting-input-3" class="form-label">Material Name: </label>
											<?php if ($update == true) : ?>
												<input id="requirement_name" type="text" name="requirement_name" class="form-control" required readonly value="<?php echo $requirement_name; ?>">
											<?php else : ?>
												<select id="material" name="material" class="form-select" onchange='fetch_select(this.value)' required>
													<option disabled selected>-- Choose Material -- </option>
													<?php
													$sql = "SELECT * FROM materials WHERE stocks != 0 ORDER by name ASC";
													$result = mysqli_query($conn, $sql);
													$count = mysqli_num_rows($result);

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
														<option value="0">No Material Found</option>
													<?php
													}
													?>
												</select>
											<?php endif ?>
										</fieldset>
									</div>

									<input id="project_id" type="hidden" name="project_id" class="form-control" required readonly value="<?php echo $project_id; ?>">
									<input id="project_name" type="hidden" name="project_name" class="form-control" required readonly value="<?php echo $project_name; ?>">
									<input id="requirement_id" type="hidden" name="requirement_id" class="form-control" required readonly value="<?php echo $requirements_id; ?>">
									<input id="material_price" type="hidden" name="material_price" class="form-control" required readonly value="<?php echo $material_price; ?>">
									<input id="category_id" type="hidden" name="category_id" class="form-control" required readonly>
									<input id="category_name" type="hidden" name="category_name" class="form-control" required readonly>
									<input id="name" type="hidden" name="name" class="form-control" required readonly>
									<input id="description" type="hidden" name="description" class="form-control" required readonly>
									<input id="unit" type="hidden" name="unit" class="form-control" required readonly>
									<input id="price" type="hidden" name="price" class="form-control" required readonly>
									<div class="mb-3">
										<label for="setting-input-2" class="form-label">Stocks: </label>
										<?php if ($update == true) : ?>
											<input id="material_stocks" type="text" name="material_stocks" class="form-control" required readonly value="<?php echo $material_stocks; ?>">
										<?php else : ?>
											<input id="stocks" type="text" name="stocks" class="form-control" required readonly>
										<?php endif ?>
									</div>
									<div class="mb-3">
										<label for="setting-input-2" class="form-label">Quantity: </label>
										<input id="quantity" type="number" name="quantity" class="form-control" <?php if ($update == true) : ?> value="<?php echo $quantity; ?>" <?php endif ?> autocomplete="off" required>
										<?php if ($update == true) : ?>
											<input id="old_quantity" type="hidden" name="old_quantity" class="form-control" required readonly value="<?php echo $quantity; ?>">
										<?php endif ?>
									</div>
									<?php if ($update == true) : ?>
										<button type="submit" name="update" class="btn app-btn-primary">Update</button>
									<?php else : ?>
										<button type="submit" name="submit" class="btn app-btn-primary">Add</button>
									<?php endif ?>
									<a href="project-requirements.php?ID=<?php echo $project_id; ?>" class="btn app-btn-primary" style="float:right;background-color:grey">Cancel</a>
								</form>
							</div>
							<!--//app-card-body-->
						</div>
					</div>

					<div class="col-12 col-md-8">
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<form class="settings-form-table">
									<div class="mb-3">
										<table id="myTable" class="table app-table-hover mb-0 text-left">
											<thead>
												<tr>
													<th class="cell">Material</th>
													<th class="cell">Quantity</th>
													<th class="cell">Actions</th>
													<th class="cell"></th>
												</tr>
											</thead>
											<tbody>
												<?php

												$id = $_GET['ID'];

												$sql = "SELECT * FROM materials, requirements WHERE materials.id = requirements.material_id AND project_id = $id";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												//check if clients exist
												if ($count > 0) {

													while ($row = mysqli_fetch_assoc($result)) {
														$id = $row['id'];
														$name = $row['name'];
														$category_name = $row['category_name'];
														$description = $row['description'];
														$quantity = $row['quantity'];
														$unit = $row['unit'];
												?>
														<tr>
															<td class="cell" style="padding-top: 1em">
																<p>Name: <b><?php echo $name; ?></b></p>
																<p><small>Category: <b><?php echo $category_name; ?></b></small></p>
																<p><small>Description: <b><?php echo $description; ?></b></small></p>
															</td>
															<td class=" cell"><?php echo $quantity . " " . $unit; ?></td>
															<td>
																<a href="project-requirements.php?ID=<?php echo $project_id; ?>&id=<?php echo $id; ?>" class="btn app-btn btn-info" style="color:white"><i class="fa fa-edit"></i> Edit</a>
															</td>
															<td>
																<a href="project-requirements.php?ID=<?php echo $project_id; ?>&delete=<?php echo $id; ?>" onclick="return confirm('Are you sure you want to remove this project material?');" class="btn app-btn btn-danger" style="color:white"><i class="fa fa-trash"></i> Remove</a>
															</td>
														</tr>
												<?php
													}
												} else {
													// echo "<script>alert('No Project Requirements Found!')</script>";
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

	<!-- fetch material details script -->
	<script>
		function fetch_select(val) {
			$.ajax({
				url: "fetch-material.php",
				type: "POST",
				data: {
					"get_option": val
				},
				dataType: "JSON",
				success: function(data) {
					$('#name').val((data[0].name));
					$('#category_id').val((data[0].category_id));
					$('#category_name').val((data[0].category_name));
					$('#description').val((data[0].description));
					$('#unit').val((data[0].unit));
					$('#stocks').val((data[0].stocks));
					$('#price').val((data[0].price));
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