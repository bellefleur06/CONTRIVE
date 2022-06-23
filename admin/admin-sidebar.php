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
						<a class="nav-link <?php if ($page == 'dashboard') {
												echo 'active';
											} ?>" href="dashboard.php">
							<span class="nav-icon"><i class="fa fa-home"></i></span>
							<span class="nav-link-text">Dashboard</span>
						</a>
						<!--//nav-link-->
					</li>
					<!--//nav-item-->
					<li class="nav-item has-submenu">
						<a class="nav-link submenu-toggle <?php if ($page == 'project' or $page == 'payable' or $page == 'receivable') {
																echo 'active';
															} ?>" href="#" data-toggle="collapse" data-target="#submenu-2" aria-expanded="<?php if ($page == 'project' or $page == 'payable' or $page == 'receivable') {
																																				echo 'true';
																																			} ?>" aria-controls="submenu-2">
							<span class="nav-icon"><i class="fas fa-folder"></i></span>
							<span class="nav-link-text ">Projects</span>
							<span class="submenu-arrow"><i class="fa fa-angle-down"></i></span>
							<!--//submenu-arrow-->
						</a>
						<!--//nav-link-->
						<div id="submenu-2" class="collapse submenu submenu-2 <?php if ($page == 'project' or $page == 'payable' or $page == 'receivable') {
																					echo 'show';
																				} ?>" data-parent="#menu-accordion">
							<ul class="submenu-list list-unstyled">
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'project') {
																					echo 'active';
																				} ?>" href="manage-projects.php">Manage Projects</a></li>
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'payable') {
																					echo 'active';
																				} ?>" href="manage-payables.php">Manage Payables</a></li>
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'receivable') {
																					echo 'active';
																				} ?>" href="manage-receivables.php">Manage Receivables</a></li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if ($page == 'invoice') {
												echo 'active';
											} ?>" href="invoice.php">
							<span class="nav-icon"><i class="fas fa-file-invoice"></i></span>
							<span class="nav-link-text">Billing</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if ($page == 'client') {
												echo 'active';
											} ?>" href="manage-clients.php">
							<span class="nav-icon"><i class="fa fa-building"></i></span>
							<span class="nav-link-text">Clients</span>
						</a>
					</li>
					<!--//nav-item-->
					<li class="nav-item">
						<a class="nav-link <?php if ($page == 'supplier') {
												echo 'active';
											} ?>" href="manage-suppliers.php">
							<span class="nav-icon"><i class="fa fa-store"></i></span>
							<span class="nav-link-text">Suppliers</span>
						</a>
					</li>
					<!--//nav-item-->
					<li class="nav-item">
						<a class="nav-link <?php if ($page == 'material') {
												echo 'active';
											} ?>" href="manage-materials.php">
							<span class="nav-icon"><i class="fas fa-archive"></i></span>
							<span class="nav-link-text">Materials</span>
						</a>
					</li>
					<!--//nav-item-->
					<li class="nav-item has-submenu">
						<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
						<a class="nav-link submenu-toggle <?php if ($page == 'receive' or $page == 'return' or $page == 'purchase') {
																echo 'active';
															} ?>" href="#" data-toggle="collapse" data-target="#submenu-5" aria-expanded="<?php if ($page == 'receive' or $page == 'return' or $page == 'purchase') {
																																				echo 'true';
																																			} ?>" aria-controls="submenu-5">
							<span class="nav-icon"><i class="fa fa-truck"></i></span>
							<span class="nav-link-text">Purchase</span>
							<span class="submenu-arrow"><i class="fa fa-angle-down"></i></span>
							<!--//submenu-arrow-->
						</a>
						<!--//nav-link-->
						<div id="submenu-5" class="collapse submenu submenu-5 <?php if ($page == 'receive' or $page == 'return' or $page == 'purchase') {
																					echo 'show';
																				} ?>" data-parent="#menu-accordion">
							<ul class="submenu-list list-unstyled">
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'receive') {
																					echo 'active';
																				} ?>" href="manage-receivings.php">Receiving</a></li>
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'return') {
																					echo 'active';
																				} ?>" href="manage-returns.php">Return</a></li>
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'purchase') {
																					echo 'active';
																				} ?>" href="manage-purchase-orders.php">Purchase Order</a></li>
							</ul>
						</div>
					</li>
					<!--//nav-item-->
					<li class="nav-item has-submenu">
						<a class="nav-link submenu-toggle <?php if ($page == 'worker' or $page == 'staff') {
																echo 'active';
															} ?>" href="#" data-toggle="collapse" data-target="#submenu-6" aria-expanded="<?php if ($page == 'worker' or $page == 'staff') {
																																				echo 'true';
																																			} ?>" aria-controls="submenu-6">
							<span class="nav-icon"><i class="fa fa-users"></i></span>
							<span class="nav-link-text">Employees</span>
							<span class="submenu-arrow"><i class="fa fa-angle-down"></i></span>
							<!--//submenu-arrow-->
						</a>
						<!--//nav-link-->
						<div id="submenu-6" class="collapse submenu submenu-6 <?php if ($page == 'worker' or $page == 'staff') {
																					echo 'show';
																				} ?>" data-parent="#menu-accordion">
							<ul class="submenu-list list-unstyled">
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'worker') {
																					echo 'active';
																				} ?>" href="manage-workers.php">Manage Workers</a></li>
								<!-- <li class="submenu-item"><a class="submenu-link" href="manage-worker-positions.php">Manage Positions</a></li> -->
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'staff') {
																					echo 'active';
																				} ?>" href="manage-staffs.php">Manage Staff</a></li>
							</ul>
						</div>
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
					<!-- <li class="nav-item has-submenu"> -->
					<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
					<!-- <a class="nav-link submenu-toggle" href="#" data-toggle="collapse" data-target="#submenu-7" aria-expanded="false" aria-controls="submenu-7">
							<span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
							<span class="nav-link-text">Reports</span> -->
					<!-- <span class="submenu-arrow"><i class="fa fa-angle-down"></i></span> -->
					<!--//submenu-arrow-->
					<!-- </a> -->
					<!--//nav-link-->
					<!-- <div id="submenu-7" class="collapse submenu submenu-7" data-parent="#menu-accordion">
							<ul class="submenu-list list-unstyled">
								<li class="submenu-item"><a class="submenu-link" href="client-reports.php">Clients</a></li>
								<li class="submenu-item"><a class="submenu-link" href="project-reports.php">Projects</a></li>
								<li class="submenu-item"><a class="submenu-link" href="supplier-reports.php">Suppliers</a></li>
								<li class="submenu-item"><a class="submenu-link" href="worker-reports.php">Workers</a></li>
								<li class="submenu-item"><a class="submenu-link" href="staff-reports.php">Staffs</a></li>
								<li class="submenu-item"><a class="submenu-link" href="purchase-report.php">Purchase</a></li>
								<li class="submenu-item"><a class="submenu-link" href="inventory-report.php">Inventory</a></li>
							</ul>
						</div> -->
					<!-- </li> -->
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