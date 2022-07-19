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
					<!-- <li class="nav-item">
						<a class="nav-link <?php if ($page == 'invoice') {
												echo 'active';
											} ?>" href="invoice.php">
							<span class="nav-icon"><i class="fas fa-file-invoice"></i></span>
							<span class="nav-link-text">Billing</span>
						</a>
					</li> -->
					<li class="nav-item has-submenu">
						<!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
						<a class="nav-link submenu-toggle <?php if ($page == 'receivable' or $page == 'payable' or $page == 'invoice') {
																echo 'active';
															} ?>" href="#" data-toggle="collapse" data-target="#submenu-4" aria-expanded="<?php if ($page == 'receivable' or $page == 'payable' or $page == 'invoice') {
																																				echo 'true';
																																			} ?>" aria-controls="submenu-4">
							<span class="nav-icon"><i class="fa fa-file-invoice"></i></span>
							<span class="nav-link-text">Billing</span>
							<span class="submenu-arrow"><i class="fa fa-angle-down"></i></span>
							<!--//submenu-arrow-->
						</a>
						<!--//nav-link-->
						<div id="submenu-4" class="collapse submenu submenu-4 <?php if ($page == 'receivable' or $page == 'payable' or $page == 'invoice') {
																					echo 'show';
																				} ?>" data-parent="#menu-accordion">
							<ul class="submenu-list list-unstyled">
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'invoice') {
																					echo 'active';
																				} ?>" href="invoice.php">Invoices</a></li>
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'receivable') {
																					echo 'active';
																				} ?>" href="manage-receivables.php">Manage Receivables</a></li>
								<li class="submenu-item"><a class="submenu-link <?php if ($page == 'payable') {
																					echo 'active';
																				} ?>" href="manage-payables.php">Manage Payables</a></li>
							</ul>
						</div>
					</li>
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