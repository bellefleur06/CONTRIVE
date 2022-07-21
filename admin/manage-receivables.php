<?php

include('../connections/config.php');

$page = 'clients';

//check if the user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title> CONTRIVE | Manage Receivables</title>

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

</head>

<body class="app">

	<?php $page = 'receivable';
	include('navbar.php'); ?>

	<div class="app-wrapper">

		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<ol class="breadcrumb mb-4" style="float:right">
					<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
					<li class="breadcrumb-item">Manage Receivables</li>
				</ol>
				<h1 class="app-page-title"><span class="nav-icon"><i class="fa fa-list"></i></span> Manage Receivables</h1>
				<!-- <a href="print-receivables.php" target="blank" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-print"></i> Print List</a> -->
				<hr class="mb-4">
				<?php
				if (isset($_SESSION['update-receivables'])) {
				?>
					<div class="alert alert-success alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['update-receivables']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['update-receivables']);
				}
				if (isset($_SESSION['receivable-not-found'])) {
				?>
					<div class="alert alert-danger alert-dismissible fade show" id="alert" role="alert">
						<strong> <?php echo $_SESSION['receivable-not-found']; ?> </strong>
						<button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php
					unset($_SESSION['receivable-not-found']);
				}
				?>
				<div class="row g-4 settings-section">
					<div class="col-12 col-md-12">
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<form class="settings-form">
									<div class="mb-3">
										<table id="myTable" class="table app-table-hover mb-0 text-center">
											<thead>
												<tr>
													<th class="cell">Receivable ID</th>
													<th class="cell">Client</th>
													<th class="cell">Total Amount</th>
													<th class="cell">Date Created</th>
													<th class="cell">Status</th>
													<th class="cell">Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$sql = "SELECT * FROM receivables WHERE total_invoice != '' ";
												$result = mysqli_query($conn, $sql);
												$count = mysqli_num_rows($result);

												//check if recievables exist
												if ($count > 0) {

													while ($row = mysqli_fetch_assoc($result)) {
														$id = $row['id'];
														$invoice_id = $row['invoice_id'];
														$client_name = $row['client_name'];
														$receivable_date_added = $row['receivable_date_added'];
														$total_invoice = $row['total_invoice'];
														$receivable_status = $row['receivable_status'];
												?>

														<tr>
															<td class="cell" style="padding-top:0.5em">#<?php echo $invoice_id; ?></td>
															<td class="cell" style="padding-top:0.5em"><?php echo $client_name; ?></td>
															<td class="cell" style="padding-top:0.5em">₱<?php echo number_format($total_invoice, 2, '.', ','); ?></td>
															<td class="cell" style="padding-top:0.5em"><?php echo date("M d, Y - h:i a", strtotime($receivable_date_added)); ?></td>
															<?php
															//check if receivable_status is unpaid
															if ($receivable_status == "Unpaid") {
															?>
																<td class="cell" style="padding-top:0.5em; font-weight:bold; color:red">Unpaid</td>

															<?php } else if ($receivable_status == "Partial") {
															?>
																<td class="cell" style="padding-top:0.5em; font-weight:bold; color:blue">Partially Paid</td>

															<?php } else if ($receivable_status == "Paid") {
															?>
																<td class="cell" style="padding-top:0.5em; font-weight:bold; color:green">Fully Paid</td>

															<?php
															}
															?>
															<td class="cell" style="padding-top:0.5em"><a href="receivable-details.php?ID=<?php echo $id; ?>" class="btn app-btn btn-success" style="color:white"><i class="fa fa-eye"></i> View</a></td>
														</tr>
												<?php
													}
												} else {
													echo "<script>alert('No Invoices Found!')</script>";
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