<?php

include('../connections/config.php');

$page = 'clients';

//check if user is logged in
if (!isset($_SESSION['username'])) {

	header('Location: ../index.php');
}

$id = $_GET['ID'];

$sql = "SELECT * FROM staffs, projects WHERE staffs.id = projects.engineer_id AND projects.id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

//check if there are project records
if ($result == TRUE) {

	$count = mysqli_num_rows($result);

	//check if project exist
	if ($count == 1) {
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
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>CONTRIVE | Project Details</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- FontAwesome JS-->
	<script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

	<!-- App CSS -->
	<link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
	<link rel="stylesheet" href="../assets/css/style.css" />

	<!-- Chart JS -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
					<li class="breadcrumb-item active">Project Details</li>
				</ol>
				<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-building"></i></span> "<?php echo $row['name']; ?>" Project Details</h1>
				<!-- <a href="project-workers.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-info" style="color:white"><i class="fa fa-users"></i> Project Workers</a>
				<a href="project-requirements.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-info" style="color:white"><i class="fa fa-tools"></i> Project Requirements</a> -->
				<hr class="mb-4">
				<div class="row g-4 settings-section">
					<div class="col-12 col-md-12">
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class=" app-card-body">
								<table class="table app-table-hover text-left">
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">Type: </label>
										</td>
										<td class="cell" style="padding-top: 1em; text-align:right">
											<label for="setting-input-3" class="form-label"><?php echo $row['type']; ?></label>
										</td>
									</tr>
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">Name: </label>
										</td>
										<td class="cell" style="padding-top: 1em; text-align:right">
											<label for="setting-input-3" class="form-label"><?php echo $row['name']; ?></label>
										</td>
									</tr>
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">Description: </label>
										</td>
										<td class="cell" style="padding-top: 1em; text-align:right">
											<label for="setting-input-3" class="form-label"><?php echo $row['project_description']; ?></label>
										</td>
									</tr>
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">Engineer: </label>
										</td>
										<td class="cell" style="padding-top: 1em; text-align:right">
											<label for="setting-input-3" class="form-label"><?php echo $row['full_name']; ?></label>
										</td>
									</tr>
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">Location: </label>
										</td>
										<td class="cell" style="padding-top: 1em; text-align:right">
											<label for="setting-input-3" class="form-label"><?php echo $row['location']; ?></label>
										</td>

									</tr>
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">Client Name: </label>
										</td>
										<td class="cell" style="padding-top: 1em; text-align:right">
											<label for="setting-input-3" class="form-label"><?php echo $row['client_name']; ?></label>
										</td>
									</tr>
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">Start Date: </label>
										</td>
										<td class="cell" style="padding-top: 1em; text-align:right">
											<label for="setting-input-3" class="form-label">
												<?php
												$start_date = $row['start_date'];
												echo $date = date("M d, Y", strtotime($start_date));
												?>
											</label>
										</td>
									</tr>
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">End Date: </label>
										</td>
										<td class="cell" style="padding-top: 1em; text-align:right">
											<label for="setting-input-3" class="form-label">
												<?php
												$end_date = $row['end_date'];
												echo $date = date("M d, Y", strtotime($end_date));
												?>
											</label>
										</td>
									</tr>
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">Status: </label>
										</td>
										<?php if ($row['status'] == 'On Hold') : ?>
											<td class="cell" style="padding-top: 1em; text-align:right; color:blue">
												<label for="setting-input-3" class="form-label"><?php echo $row['status']; ?></label>
											</td>
										<?php elseif ($row['status'] == 'Started') : ?>
											<td class="cell" style="padding-top: 1em; text-align:right; color:orange">
												<label for="setting-input-3" class="form-label"><?php echo $row['status']; ?></label>
											</td>
										<?php elseif ($row['status'] == 'Finished') : ?>
											<td class="cell" style="padding-top: 1em; text-align:right; color:green">
												<label for="setting-input-3" class="form-label"><?php echo $row['status']; ?></label>
											</td>
										<?php elseif ($row['status'] == 'Cancelled') : ?>
											<td class="cell" style="padding-top: 1em; text-align:right; color:red">
												<label for="setting-input-3" class="form-label"><?php echo $row['status']; ?></label>
											</td>
										<?php endif;  ?>

									</tr>
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">Project Contract: </label>
										</td>
										<td class="cell" style="padding-top: 1em; text-align:right">
											<label for="setting-input-3" class="form-label"><a href="view-contract.php?file=<?php echo $row['contract'];?>" target="blank" class="btn btn-success" style="color:white"><i class="fa fa-eye"></i> View</a></label>
										</td>
									</tr>
									<tr>
										<td class="cell" style="padding-top: 1em">
											<label for="setting-input-2" class="form-label">Project Blueprint: </label>
										</td>
										<td class="cell" style="padding-top: 1em; text-align:right">
											<label for="setting-input-3" class="form-label"><a href="view-contract.php?file=<?php echo $row['blueprint'];?>" target="blank" class="btn btn-success" style="color:white"><i class="fa fa-eye"></i> View</a></label>
										</td>
									</tr>
								</table>
								<form class="settings-form" action="delete-projects.php" method="post">
									<a href="update-projects.php?ID=<?php echo $row['id']; ?>" class="btn app-btn-primary"><i class="fa fa-edit"></i> Edit</a>
									<input type="hidden" name="ID" value="<?php echo $row['id']; ?>">
									<button type="submit" name="delete" class="btn app-btn btn-danger" style="color:white" onclick="return confirm('Are you sure you want to delete this project record?')">Delete</button>
									<a href="manage-projects.php" class="btn app-btn btn-info ms-auto" style="color:white; float:right"><i class="fa fa-arrow-left"></i> Go Back</a>
								</form>
							</div>
							<!--//app-card-body-->

						</div>
						<!--//app-card-->
					</div>

				</div>
				<!--//row-->

				<hr class="my-4">

				<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-chart-bar"></i></span> "<?php echo $row['name']; ?>" Project Division Progress</h1>

				<!-- <a href="project-divisions.php?ID=<?php echo $row['id']; ?>" class="btn app-btn btn-info" style="color:white"><i class=" fa fa-plus"></i> Add New Division / Project Update</a> -->
				<hr class="my-4">

				<div class="row g-4 settings-section">
					<div class="col-12 col-md-12">
						<div class="app-card app-card-settings shadow-sm p-4">
							<div class="app-card-body">
								<form class="settings-form">
									<table class="table app-table-hover text-left">
										<thead>
											<tr>
												<th class="cell">
													<h1 class="app-page-title" style="float:left">Project Divisions</h1>
												</th>
												<th class="cell">
													<h1 class="app-page-title" style="float:left">Progress (%)</h1>
												</th>
											</tr>
										</thead>
										<?php

										$id = $_GET['ID'];

										$sql = "SELECT * FROM progress WHERE project_id = $id";
										$result = mysqli_query($conn, $sql);
										$count = mysqli_num_rows($result);

										//check if clients exist
										if ($count > 0) {

											while ($row = mysqli_fetch_assoc($result)) {
												$division_name = $row['division_name'];
												$progress = $row['progress'];

												$total =  100;
												$percent = round(($progress / $total) * 100, 1);
										?>
												<tr>
													<td class="cell" style="padding-top: 1em">
														<label for="setting-input-2" class="form-label"><?php echo $division_name; ?></label>
													</td>
													<?php if ($progress == "") { ?>
														<td class="cell" style="padding-top: 1em">
															<div id="outter" style="width:100%; 
															background-color:#ddd;height:30px; text-align:center;padding-left:5px;padding-left:5px; line-height:30px; color:black;">
																<?php echo $progress . "0%"; ?>
															</div>
														</td>
													<?php } else if ($progress == 0) { ?>
														<td class="cell" style="padding-top: 1em">
															<div id="outter" style="width:100%; background-color:#ddd; height:30px; text-align:left; padding-left:5px; line-height:30px; color:black;">
																<?php echo $percent . "%"; ?>
															</div>
														</td>
													<?php } else if ($progress == 100) { ?>
														<td class="cell" style="padding-top: 1em">
															<div id="outter" style="width:100%; background-color:#ddd">
																<div id="inner" class="progress-bar progress-bar-striped bg-success" style="width:<?php echo $percent; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
																	<?php echo $percent . "%"; ?>
																</div>
															</div>
														</td>
													<?php } else { ?>
														<td class="cell" style="padding-top: 1em">
															<div id="outter" style="width:100%; background-color:#ddd">
																<div id="inner" class="progress-bar progress-bar-striped bg-warning" style="width:<?php echo $percent; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
																	<?php echo $percent . "%"; ?>
																</div>
															</div>
														</td>
													<?php } ?>
												</tr>
											<?php
											}
										} else {
											?>
											<tr>
												<td class="text-center" colspan="5" style="font-weight:bold; font-size: 1.2em">No data available in the table</td>
											</tr>
										<?php
										}
										?>
										<?php

										$id = $_GET['ID'];

										$sql = "SELECT ROUND(AVG(progress), 0) AS total_progress FROM progress WHERE project_id = '$id'";
										$result = mysqli_query($conn, $sql);
										$row = mysqli_fetch_assoc($result);

										$total_progress = $row['total_progress'];

										?>
										<tr>

											<td class="cell" style="padding-top: 1em; font-weight: bold; font-size:1.1em">Total Project Progress</td>
											<?php if ($total_progress == "") { ?>
												<td class="cell" style="padding-top: 1em">
													<div id="outter" style="width:100%; 
													background-color:#ddd;height:30px; text-align:center; line-height:30px; color:black;">
														<?php echo $total_progress . "0%"; ?>
													</div>
												</td>
											<?php } else if ($total_progress == 0) { ?>
												<td class="cell" style="padding-top: 1em">
													<div id="outter" style="width:100%; 
													background-color:#ddd;height:30px; text-align:center; line-height:30px; color:black;">
														<?php echo $total_progress . "%"; ?>
													</div>
												</td>
											<?php } else if ($total_progress == 100) { ?>
												<td class="cell" style="padding-top: 1em">
													<div id="outter" style="width:100%; 
													background-color:#ddd">
														<div id="inner" class="progress-bar progress-bar-striped bg-success" style="width:<?php echo $total_progress; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
															<?php echo $total_progress . "%"; ?>
														</div>
													</div>
												</td>
											<?php } else { ?>
												<td class="cell" style="padding-top: 1em">
													<div id="outter" style="width:100%; background-color:#ddd">
														<div id="inner" class="progress-bar progress-bar-striped bg-info" style="width:<?php echo $total_progress; ?>%; height:30px; text-align:center; line-height:30px; color:white;">
															<?php echo $total_progress . "%"; ?>
														</div>
													</div>
												</td>
											<?php } ?>
										</tr>
									</table>
								</form>
							</div>
							<!--//app-card-body-->

						</div>
						<!--//app-card-->
					</div>
				</div>
				<!--//row-->

				<hr class="my-4">

				<?php
				$id = $_GET['ID'];

				$sql = "SELECT * FROM staffs, projects WHERE staffs.id = projects.engineer_id AND projects.id = '$id'";
				$result = mysqli_query($conn, $sql);
				$row = mysqli_fetch_assoc($result);

				//check if there are project records
				if ($result == TRUE) {

					$count = mysqli_num_rows($result);

					//check if project exist
					if ($count == 1) {
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
				?>


				<h1 class="app-page-title text-success"><span class="nav-icon"><i class="fa fa-chart-bar"></i></span> "<?php echo $row['name']; ?>" Project Updates</h1>

				<div class="row g-4 settings-section">

					<?php

					function time_elapsed_string($datetime, $full = false)
					{
						$now = new DateTime;
						$ago = new DateTime($datetime, new DateTimeZone('Asia/Manila'));
						$diff = $now->diff($ago);

						$diff->w = floor($diff->d / 7);
						$diff->d -= $diff->w * 7;

						$string = array(
							'y' => 'year',
							'm' => 'month',
							'w' => 'week',
							'd' => 'day',
							'h' => 'hour',
							'i' => 'minute',
							's' => 'second',
						);
						foreach ($string as $k => &$v) {
							if ($diff->$k) {
								$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
							} else {
								unset($string[$k]);
							}
						}

						if (!$full) $string = array_slice($string, 0, 1);
						return $string ? implode(', ', $string) . ' ago' : 'just now';
					}

					$id = $_GET['ID'];

					$sql = "SELECT * FROM updates, staffs WHERE updates.user_id = staffs.id AND updates.project_id = '$id' ORDER by update_id DESC";
					$result = mysqli_query($conn, $sql);
					$count = mysqli_num_rows($result);

					if ($count > 0) {

						while ($row = mysqli_fetch_assoc($result)) {
							$update_id =  $row['update_id'];
							$project_id =  $row['project_id'];
							$division_name =  $row['division_name'];
							$progress =  $row['progress'];
							$details =  $row['details'];
							$user_id =  $row['user_id'];
							$date_posted =  $row['date_posted'];
					?>
							<div class="col-12 col-md-6">
								<div class="app-card app-card-settings shadow-sm p-4">
									<div class="app-card-body">
										<form class="settings-form">
											<table class="table w-100">
												<tbody>
													<tr>
														<td>
															<h5 style="color:#5b99ea"><?php echo $division_name . " - " . $progress . "% (Total Progress)"; ?></h5>
															<hr>
															<p><i class="fas fa-calendar"> </i>
																<i> <?php echo $date = date("d F Y", strtotime($date_posted)); ?> <span style="color:#bbb; padding-left:5px;">
																		<?php
																		echo time_elapsed_string($date_posted);
																		?></span></i>
															<p><b>Posted by: </b>
																<a style="color:#5b99ea">
																	<?php
																	echo $row['full_name'];
																	?>
																</a>
															</p>
															<p><?php echo substr($details, 0, 200) . '...'; ?></p>
															<a href="project-update-details.php?ID=<?php echo $project_id; ?>&update=<?php echo $update_id; ?>" class="btn app-btn btn-info" style="color:white; float:right"><i class="fa fa-eye"></i> View</a>
														</td>
													</tr>
												</tbody>
											</table>
										</form>
									</div>
									<!--//app-card-body-->
								</div>
								<!--//app-card-->
							</div>
					<?php
						}
					} else {
						echo '<h1 class="app-page-title text-center" style="float:left; color:#d26d69; font-size:2rem;">No Updates Found!</h1>';
					}
					?>
				</div>
				<!--//row-->

				<hr class="my-4">

				<!-- <div class="row g-4 settings-section">
					<div>
						<canvas id="myChart"></canvas>
					</div>
				</div> -->

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
	const labels = ['General Requirements', 'Site Construction', 'Concrete', 'Masonry', 'Metals', 'Wood & Plastics', 'Doors & Windows', 'Finishes', 'Furnishings', 'Mechanical', 'Electrical', 'Total'];
	const data = {
		labels: labels,
		datasets: [{
			label: 'Divisions',
			data: [100, 95, 90, 88, 86, 75, 40, 36, 25, 20, 18, 65],
			backgroundColor: 'rgba(54, 162, 235, 0.2)',
			borderColor: 'rgb(54, 162, 235)',
			borderWidth: 1
		}]
	};

	const config = {
		type: 'bar',
		data: data,
		options: {
			scales: {
				y: {
					beginAtZero: true,
					grace: '5%'
				}
			}
		},
	};

	Chart.defaults.font.size = 14;
	var myChart = new Chart(
		document.getElementById('myChart'),
		config
	);
</script> -->

</html>