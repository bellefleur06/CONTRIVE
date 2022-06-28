<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sidebar</title>
</head>

<body>

	<div id="app-sidepanel" class="app-sidepanel">
		<div id="sidepanel-drop" class="sidepanel-drop">

		</div>
		<div class="sidepanel-inner d-flex flex-column">
			<a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
			<div class="app-branding">
				<a class="app-logo" href="dashboard.php"><img src="../assets/images/kcs.png" style="height:45px; "></a>
			</div>
			<!--//app-branding-->

			<nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
				<ul class="app-menu list-unstyled accordion" id="menu-accordion">
					<li class="nav-item">
						<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
						<a class="nav-link <?php if ($page == 'dashboard') {
												echo 'active';
											} ?>" href="dashboard.php">
							<span class="nav-icon"><i class="fa fa-home"></i></span>
							<span class="nav-link-text">Dashboard</span>
						</a>
						<!--//nav-link-->
					</li>
					<!--//nav-item-->
					<li class="nav-item">
						<a class="nav-link <?php if ($page == 'project') {
												echo 'active';
											} ?>" href="manage-projects.php">
							<span class="nav-icon"><i class="fa fa-building"></i></span>
							<span class="nav-link-text">Projects</span>
						</a>
					</li>
					<!--//nav-item-->
					<li class="nav-item">
						<a class="nav-link <?php if ($page == 'workers') {
												echo 'active';
											} ?>" href="workers.php">
							<span class="nav-icon"><i class="fa fa-users"></i></span>
							<span class="nav-link-text">Workers</span>
						</a>
					</li>
					<!--//nav-item-->

					<li class="nav-item">
						<a class="nav-link <?php if ($page == 'report') {
												echo 'active';
											} ?>" href="client-reports.php">
							<span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
							<span class="nav-link-text">Reports</span>
						</a>
					</li>
					<!--//nav-item-->
				</ul>
				<!--//app-menu-->
			</nav>
			<!--//app-nav-->
		</div>
		<!--//sidepanel-inner-->
	</div>
	<!--//app-sidepanel-->

</body>

</html>