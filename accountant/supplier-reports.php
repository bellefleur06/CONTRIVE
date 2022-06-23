<?php

include('../connections/config.php');

if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Supplier Reports</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link id="theme-style" rel="stylesheet" href="dataTables/jquery.dataTables.min.css">
	<!-- FontAwesome JS-->
	<script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

	<!-- App CSS -->
	<link id="theme-style" rel="stylesheet" href="../assets/css/portal.css">
	<link rel="stylesheet" href="../assets/css/style.css" />

	<!-- Chart JS -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body class="app">

	<?php $page = 'report';
	include('accountant-navbar.php'); ?>

	<div class="app-wrapper">

		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<!-- TABS -->
				<nav id="reports-table-tab" class="reports-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
					<a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="client-reports.php" role="tab" data-target="#client" aria-selected="true">Clients</a>
					<a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="project-reports.php" role="tab" aria-selected="false">Projects</a>
					<a class="flex-sm-fill text-sm-center nav-link active" data-bs-toggle="tab" href="supplier-reports.php" role="tab" aria-selected="false">Suppliers</a>
					<a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="worker-reports.php" role="tab" aria-selected="false">Workers</a>
					<a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="staff-reports.php" role="tab" aria-selected="false">Staffs</a>
					<a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="purchase-report.php" role="tab" aria-selected="false">Purchase</a>
					<a class="flex-sm-fill text-sm-center nav-link" data-bs-toggle="tab" href="billing-report.php" role="tab" aria-selected="false">Billing</a>
				</nav>
				<!-- TABS -->
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item active">Reports</li>
					<li class="breadcrumb-item active">Supplier Reports</li>
				</ol>
				<h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Supplier Reports</h1>
				<!-- <a href="print-clients.php" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-print"></i> Print Report</a> -->

				<hr class="mb-4">
				<span style="font-weight:bold">Filter Records By:</span>
				<a href="supplier-reports.php" class="btn app-btn btn-info" style="margin-bottom: 0.5em; color:white"><i class="fa fa-calendar"></i> Month and Year</a>
				<a href="supplier-reports-filter-by-month-only.php" class="btn app-btn" style="margin-bottom: 0.5em; border:1px solid #000"><i class="fa fa-calendar"></i> Month Only</a>
				<a href="supplier-reports-filter-by-year-only.php" class="btn app-btn" style="margin-bottom: 0.5em; border:1px solid #000"><i class="fa fa-calendar"></i> Year Only</a>
				<a href="supplier-reports-filter-by-two-dates.php" class="btn app-btn" style="margin-bottom: 0.5em; border:1px solid #000"><i class="fa fa-calendar"></i> Between Two Dates</a>
				<hr class="mb-4">

				<form class="settings-form" method="post">
					<div class="row">
						<div class="col-md-6 col-12 mb-3">
							<label for="setting-input-3" class="form-label" style="font-weight:bold">Month: </label>
							<select name="month" class="form-select">
								<option disabled selected>-- Select Month --</option>
								<?php
								for ($i = 1; $i <= 12; $i++) {
									$month = date('F', mktime(0, 0, 0, $i, 1, 2019));
								?>
									<option value="<?php echo $i; ?>" <?= (isset($_POST['month']) && $_POST['month'] == $i) ? 'selected' : '' ?>><?php echo $month; ?></option>
								<?php
								}
								?>
							</select>
						</div>
						<div class="col-md-6 col-12 mb-3">
							<label for="setting-input-3" class="form-label" style="font-weight:bold">Year: </label>
							<select name="year" class="form-select">
								<option disabled selected>-- Select Year --</option>
								<?php
								for ($n = 2019; $n <= 2030; $n++) {
									$month = date('F', mktime(0, 0, 0, $n, 1, 2019));
								?>
									<option value="<?php echo $n; ?>" <?= (isset($_POST['year']) && $_POST['year'] == $n) ? 'selected' : '' ?>><?php echo $n; ?></option>
								<?php
								}
								?>
							</select>
						</div>
						<div class="mb-3">
							<button type="submit" name="submit" class="btn app-btn btn-info" style="color:#fff">Filter</button>
							<a href="supplier-reports.php" class="btn app-btn btn-danger" style="color:#fff">Clear</a>
						</div>
					</div>
				</form>

				<?php if (isset($_POST['submit'])) {

					$month = mysqli_real_escape_string($conn, $_POST['month']);
					$year = mysqli_real_escape_string($conn, $_POST['year']);

				?>
					<div class="row g-4 settings-section">
						<div class="col-12 col-md-12">
							<div class="app-card app-card-settings shadow-sm p-4">
								<div class="app-card-body">
									<div class="mb-3">
										<table id="myTable" class="table app-table-hover mb-0 text-left">
											<thead>
												<?php
												$sql = "SELECT * FROM suppliers WHERE month(date_added) = '$month' AND year(date_added) = '$year' GROUP BY name ORDER BY date_added";
												$result = mysqli_query($conn, $sql);
												?>
												<tr>
													<th class="cell">Name</th>
													<th class="cell">Category</th>
													<th class="cell">Date Added</th>
													<th class="cell">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php while ($row = mysqli_fetch_array($result)) { ?>
													<tr>
														<td class="cell" style="padding-top: 0.5em"><?php echo $row['name']; ?></td>
														<td class="cell" style="padding-top: 0.5em"><?php echo $row['category_name']; ?></td>
														<td class="cell" style="padding-top: 0.5em"><?php echo $date = date("M d, Y", strtotime($row['date_added'])); ?></td>
														<td><a href="supplier-report-details.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-success" style="color:white"><i class="fa fa-eye"></i> View</a></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
								<!--//app-card-body-->

							</div>
							<!--//app-card-->
						</div>
					</div>

				<?php
				} else {
				?>
					<div class="row g-4 settings-section">
						<div class="col-12 col-md-12">
							<div class="app-card app-card-settings shadow-sm p-4">
								<div class="app-card-body">
									<div class="mb-3">
										<table id="myTable" class="table app-table-hover mb-0 text-left">
											<thead>
												<?php
												$sql = "SELECT * FROM suppliers GROUP BY name ORDER BY date_added";
												$result = mysqli_query($conn, $sql);
												?>
												<tr>
													<th class="cell">Name</th>
													<th class="cell">Category</th>
													<th class="cell">Date Added</th>
													<th class="cell">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php while ($row = mysqli_fetch_array($result)) { ?>
													<tr>
														<td class="cell" style="padding-top: 0.5em"><?php echo $row['name']; ?></td>
														<td class="cell" style="padding-top: 0.5em"><?php echo $row['category_name']; ?></td>
														<td class="cell" style="padding-top: 0.5em"><?php echo $date = date("M d, Y", strtotime($row['date_added'])); ?></td>
														<td><a href="supplier-report-details.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-success" style="color:white"><i class="fa fa-eye"></i> View</a></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
								<!--//app-card-body-->

							</div>
							<!--//app-card-->
						</div>
					</div>
				<?php
				}
				?>

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

</html>