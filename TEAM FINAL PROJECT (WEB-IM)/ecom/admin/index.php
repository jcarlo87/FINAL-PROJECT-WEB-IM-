<?php
require_once "../database.php";
session_start();
if (isset($_SESSION["admin"])) {
  header("Location:product.php");
}

//log in account
if (isset($_POST["login"])) {
  $email = mysqli_real_escape_string($conn, $_POST['admin_email']);
  $password = $_POST["admin_password"];

  $sql = "SELECT * FROM admin WHERE admin_email = '$email'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
    if ($password === $admin["admin_password"]) {
      $_SESSION['admin_email'] = $admin['admin_email'];
      $_SESSION['admin_id'] = $admin['admin_id'];
      $_SESSION['admin_name'] = $admin['admin_name'];
      $_SESSION['admin'] = "yes";
      header('location:product.php');
    }
  } 
  echo "<script>alert('incorrect email or password')</script>";

}
?>

<!--ADMIN PANEL LOG IN PAGE -->

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login</title>
  <link rel="stylesheet" href="../css/general.css" />
  <script src="../js/script.js"></script>
</head>

<body>
  <div id="admin-sign-account-page-wrapper">
    <!-- login page background-image -->
    <div class="sign-account-bg">
      <img class="sign-account-bg-img" src="../images/log-in/login-bg.jpg" />
    </div>
    <div class="form-wrapper">
      <!-- login form -->
      <form action="" method="post">
        <h1>Admin Login</h1>
        <p>Please enter your login details to sign in.</p>
        <input type="email" id="admin_email" name="admin_email" placeholder="Email Address" required />

        <div class="password-wrapper">
          <input type="password" id="admin_password" name="admin_password" placeholder="Password" required />
          <input type="checkbox" onclick="toggle('admin_password')">
        </div>

        <div class="checkboxAndForgotPass-wrapper">
          <div class="checkbox-wrapper">
            <input type="checkbox" id="keep-me-login" name="keep-me-login" value="#" />
            <label>Keep me logged in</label>
          </div>
          <a href="#">Forgot password?</a>
        </div>
        <input type="submit" name="login" id="login" value="Log in" />
      </form>
    </div>
  </div>
</body>

</html>