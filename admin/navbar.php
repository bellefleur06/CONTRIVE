<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Navbar</title>
	<script>
		function display_ct7() {
			var x = new Date()
			var ampm = x.getHours() >= 12 ? ' PM' : ' AM';
			hours = x.getHours() % 12;
			hours = hours ? hours : 12;
			hours = hours.toString().length == 1 ? 0 + hours.toString() : hours;

			var minutes = x.getMinutes().toString()
			minutes = minutes.length == 1 ? 0 + minutes : minutes;

			var seconds = x.getSeconds().toString()
			seconds = seconds.length == 1 ? 0 + seconds : seconds;

			var month = (x.getMonth() + 1).toString();
			month = month.length == 1 ? 0 + month : month;

			var dt = x.getDate().toString();
			dt = dt.length == 1 ? 0 + dt : dt;

			var x1 = month + "/" + dt + "/" + x.getFullYear();
			x1 = x1 + " - " + hours + ":" + minutes + ":" + seconds + " " + ampm;
			document.getElementById('ct7').innerHTML = x1;
			display_c7();
		}

		function display_c7() {
			var refresh = 1000; // Refresh rate in milli seconds
			mytime = setTimeout('display_ct7()', refresh)
		}

		display_c7()
	</script>
</head>

<body>
	<header class="app-header fixed-top">
		<div class="app-header-inner">
			<div class="container-fluid py-2">
				<div class="app-header-content">
					<div class="row justify-content-between align-items-center">

						<div class="col-auto">

							<a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
								<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
									<title>Menu</title>
									<path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
								</svg>
							</a>
							<span style="font-weight:bold">Welcome, <?php echo $_SESSION['access']; ?></span>

							<!-- <span id="ct7" style="font-weight:bold; padding-left: 36em">Current Date and Time</span> -->
						</div>
						<!--//col-->



						<div class="app-utilities col-auto">

							<div class="app-utility-item app-user-dropdown dropdown">
								<a class="dropdown" id="user-dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
									<span><i class="fa fa-bell"></i></span> &nbsp
									<span><a href="settings.php"><i class="fas fa-cog" role="button"></i></span>
								</a> &nbsp

								<div class="app-utility-item app-user-dropdown dropdown">
									<a class="dropdown-toggle" id="user-dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
										<img src="../staff_images/<?php echo $_SESSION['profile']; ?>" alt="" srcset="" style="border-radius: 50%"></a>
									<ul class="dropdown-menu" aria-labelledby="user-dropdown-toggle">
										<li><a class="dropdown-item" href="manage-account.php">
												<i class="far fa-user"></i> &nbsp
												<span>Profile</span>
											</a></li>
										<li>
										<li><a class="dropdown-item" href="user-logs.php">
												<i class="fas fa-clipboard"></i> &nbsp
												<span>User Logs</span>
											</a></li>
										<li><a class="dropdown-item" href="audit-trail.php">
												<i class="fas fa-bars"></i> &nbsp
												<span>Audit Trail</span>
											</a></li>
										<li>
											<hr class="dropdown-divider" />
										</li>
										<li><a class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to logout?')" href="logout.php">
												<i class='fas fa-sign-out-alt'></i> &nbsp
												<span>Log Out</span>
											</a></li>
									</ul>

								</div>
								<!--//app-user-dropdown-->

							</div>
							<!--//app-utilities-->
						</div>
						<!--//row-->
					</div>
					<!--//app-header-content-->
				</div>
				<!--//container-fluid-->
			</div>
			<!--//app-header-inner-->
			<?php include('admin-sidebar.php'); ?>
	</header>
	<!--//app-header-->
</body>

</html>