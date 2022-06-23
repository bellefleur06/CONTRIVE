<?php

include('connections/config.php');

error_reporting(0);

//check if user is already logged in
if (isset($_SESSION['username'])) {
  header("Location: admin/dashboard.php");
}

//check if login button is clicked
if (isset($_POST['submit'])) {

  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, md5($_POST['password']));

  $sql = "SELECT * FROM staffs WHERE username = '$username' AND password = '$password'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  $count = mysqli_num_rows($result);

  //check if user record is existing in db
  if ($count == 1) {

    $_SESSION['username'] = $username;
    $_SESSION['id'] = $row['id'];
    $_SESSION['access'] = $row['access'];

    $sql = "UPDATE staffs SET last_login = now() WHERE id = '{$_SESSION['id']}'";
    $result = mysqli_query($conn, $sql);

    //check if remember me checkbox is checked
    if (!empty($_POST['remember'])) {

      $remember = $_POST['remember'];

      //Set Cookie
      setcookie('username', $_POST['username'], time() + 3600 * 24 * 7);
      setcookie('password', $_POST['password'], time() + 3600 * 24 * 7);
    } else {

      //Expire Cookie
      setcookie('username', $_POST['username'], 30);
      setcookie('password', $_POST['password'], 30);
    }

    //check if user is logged in as admin 
    if ($_SESSION['access'] == "Admin") {

      echo "<script>alert('Welcome Back Admin!');window.location.replace('admin/dashboard.php');</script>";

      //clear textboxes if log in is successful
      $_POST['username'] = "";
      $_POST['password'] = "";

      //icheck if user is logged in as staff
    } else if ($_SESSION['access'] == "Staff") {

      echo "<script>alert('Welcome Back Staff!');window.location.replace('staff/dashboard.php');</script>";

      $_POST['username'] = "";
      $_POST['password'] = "";
    }
  } else {

    $_SESSION['login'] = "Invalid Username or Password";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Login</title>

  <!-- Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- FontAwesome JS-->
  <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

  <!-- App CSS -->
  <link id="theme-style" rel="stylesheet" href="assets/css/portal.css" />
  <link rel="stylesheet" href="assets/css/style.css" />

  <!-- <script src="assets/js/script.js"></script> -->


</head>

<body class="app app-login p-0">
  <div class="row g-0 app-auth-wrapper">
    <div class="col-12 col-md-12 col-lg-12 auth-main-col text-center p-5">
      <div class="d-flex flex-column align-content-end">
        <div class="app-auth-body mx-auto bg-white p-5 shadow">
          <div class="app-auth-branding mb-4"></div>
          <h2 class="auth-heading text-center mb-4">
            CONTRIVE: <br> RECORD AND BILLING MANAGEMENT SYSTEM
          </h2>
          <?php
          if (isset($_SESSION['login'])) {
          ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong> <?php echo $_SESSION['login']; ?> </strong>
              <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php
            unset($_SESSION['login']);
          }
          ?>
          <div class="auth-form-container text-left">
            <form class="auth-form login-form" method="post">
              <div class="form-group mb-3">
                <label class="sr-only" for="email">Username</label>
                <input id="email" name="username" type="text" class="form-control signin-email" autocomplete="off" required placeholder="Username" value="<?php if (isset($_COOKIE['username'])) {
                                                                                                                                                            echo $_COOKIE['username'];
                                                                                                                                                          }; ?>" />
              </div>
              <!--//form-group-->
              <div class="form-group mb-3">
                <label class="sr-only" for="password">Password</label>
                <div class="input-group">
                  <input id="password" name="password" type="password" class="form-control signin-password" autocomplete="off" required placeholder="Password" value="<?php if (isset($_COOKIE['password'])) {
                                                                                                                                                                        echo $_COOKIE['password'];
                                                                                                                                                                      }; ?>" />
                  <!-- <span class="input-group-text"><i class="far fa-eye" id="togglePassword" style="cursor:pointer"></i></span> -->
                </div>
              </div>
              <!--//form-group-->

              <div class="extra my-3 row justify-content-between">
                <div class="col-6">
                  <div class="form-check">
                    <input class="form-check-input" name="remember" type="checkbox" id="RememberPassword" />
                    <label class="form-check-label" for="RememberPassword">
                      Remember me
                    </label>
                  </div>
                </div>
                <!--//col-6-->
                <div class="col-6">
                  <div class="forgot-password text-right">
                    <a href="#">Forgot Your Password?</a>
                  </div>
                </div>
                <!--//col-6-->
              </div>
              <!--//extra-->

              <div class="text-center">
                <button type="submit" name="submit" class="btn app-btn-primary btn-block theme-btn mx-auto">
                  Log In
                </button>
              </div>
            </form>
          </div>
          <!--//auth-form-container-->
        </div>
        <!--//auth-body-->
      </div>
      <!--//flex-column-->
    </div>
    <!--//auth-main-col-->
  </div>
  <!--//row-->
</body>

<script>
  const togglePassword = document.querySelector("#togglePassword");
  const password = document.querySelector("#password");

  togglePassword.addEventListener("click", function() {
    //toggle the type attribute
    const type =
      password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    //toggle the type attribute
    this.classList.toggle("fa-eye");
    this.classList.toggle("fa-eye-slash");
  });
</script>

</html>
<?php

include('connections/logs.php');

?>