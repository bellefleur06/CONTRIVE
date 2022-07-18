<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
						<!--//emerut-->
							<div class="app-utility-item app-user-dropdown dropdown">
								<a class="dropdown" id="user-dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
									<!-- <span><i class="fa fa-bell"></i></span> &nbsp -->
									<!-- <span><a href="settings.php"><i class="fas fa-cog" role="button"></i></span> -->
									<span><a href="settings.php">
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-gear icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M8.837 1.626c-.246-.835-1.428-.835-1.674 0l-.094.319A1.873 1.873 0 0 1 4.377 3.06l-.292-.16c-.764-.415-1.6.42-1.184 1.185l.159.292a1.873 1.873 0 0 1-1.115 2.692l-.319.094c-.835.246-.835 1.428 0 1.674l.319.094a1.873 1.873 0 0 1 1.115 2.693l-.16.291c-.415.764.42 1.6 1.185 1.184l.292-.159a1.873 1.873 0 0 1 2.692 1.116l.094.318c.246.835 1.428.835 1.674 0l.094-.319a1.873 1.873 0 0 1 2.693-1.115l.291.16c.764.415 1.6-.42 1.184-1.185l-.159-.291a1.873 1.873 0 0 1 1.116-2.693l.318-.094c.835-.246.835-1.428 0-1.674l-.319-.094a1.873 1.873 0 0 1-1.115-2.692l.16-.292c.415-.764-.42-1.6-1.185-1.184l-.291.159A1.873 1.873 0 0 1 8.93 1.945l-.094-.319zm-2.633-.283c.527-1.79 3.065-1.79 3.592 0l.094.319a.873.873 0 0 0 1.255.52l.292-.16c1.64-.892 3.434.901 2.54 2.541l-.159.292a.873.873 0 0 0 .52 1.255l.319.094c1.79.527 1.79 3.065 0 3.592l-.319.094a.873.873 0 0 0-.52 1.255l.16.292c.893 1.64-.902 3.434-2.541 2.54l-.292-.159a.873.873 0 0 0-1.255.52l-.094.319c-.527 1.79-3.065 1.79-3.592 0l-.094-.319a.873.873 0 0 0-1.255-.52l-.292.16c-1.64.893-3.433-.902-2.54-2.541l.159-.292a.873.873 0 0 0-.52-1.255l-.319-.094c-1.79-.527-1.79-3.065 0-3.592l.319-.094a.873.873 0 0 0 .52-1.255l-.16-.292c-.892-1.64.902-3.433 2.541-2.54l.292.159a.873.873 0 0 0 1.255-.52l.094-.319z"/>
										<path fill-rule="evenodd" d="M8 5.754a2.246 2.246 0 1 0 0 4.492 2.246 2.246 0 0 0 0-4.492zM4.754 8a3.246 3.246 0 1 1 6.492 0 3.246 3.246 0 0 1-6.492 0z"/>
										</svg></span>
								</a> &nbsp
								
								<?php

									$sql = "SELECT * FROM staffs WHERE id = '{$_SESSION['id']}'";
									$result = mysqli_query($conn, $sql);
									$row = mysqli_fetch_assoc($result);

								?>
							<div class="app-utility-item app-user-dropdown dropdown">
								<a class="dropdown-toggle" id="user-dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
									<img src="../staff_images/<?php echo $_SESSION['profile']; ?>" alt="" srcset="" style="border-radius: 50%"></a>
								<ul class="dropdown-menu" aria-labelledby="user-dropdown-toggle">
									<li><a class="dropdown-item" href="manage-account.php">
											<i class="far fa-user"></i> &nbsp
											<span>Profile</span>
										</a></li>
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
				</div>
				<!--//app-header-content-->
			</div>
			<!--//container-fluid-->
		</div>
		<!--//app-header-inner-->
		<?php include('accountant-sidebar.php'); ?>
	</header>
	<!--//app-header-->
</body>

</html>