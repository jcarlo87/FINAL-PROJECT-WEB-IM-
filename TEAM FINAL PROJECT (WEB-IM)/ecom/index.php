<?php
include 'database.php';
session_start();
if (isset($_SESSION["customer"])) {
  header("Location:customer/index.php"); //the home page
}

//log in account
if (isset($_POST["login"])) {
  $email = mysqli_real_escape_string($conn, $_POST['customer_email']);
  $password = $_POST['customer_password'];
  require_once "database.php";

  $sql = "SELECT * FROM customers WHERE customer_email = '$email'";
  $result = mysqli_query($conn, $sql);


  if (mysqli_num_rows($result) > 0) {
    $customer = mysqli_fetch_assoc($result);
    if (password_verify($password, $customer["customer_password"])) {
      $_SESSION['customer_name'] = $customer['customer_name'];
      $_SESSION['customer_email'] = $customer['customer_email'];
      $_SESSION['customer_id'] = $customer['customer_id'];
      $_SESSION['customer'] = "yes";
      header('location:customer/index.php');
    } 
  } else {
    echo "<script>alert('incorrect email or password')</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="css/general.css" />
</head>

<body>
  <div id="sign-account-page-wrapper">
    <!-- login page background-image -->
    <div class="sign-account-bg">
      <img class="sign-account-bg-img" src="images/log-in/login-bg.jpg" />
    </div>
    <div class="form-wrapper">

      <!-- login form -->
      <form action="" method="post">
        <h1>Login</h1>
        <p>Please enter your login details to sign in.</p>
        <input type="email" id="customer_email" name="customer_email" placeholder="Email Address" required />

        <input type="password" id="customer_password" name="customer_password" placeholder="Password" required />

        <div class="checkboxAndForgotPass-wrapper">
          <div class="checkbox-wrapper">
            <input type="checkbox" id="keep-me-login" name="keep-me-login" value="#" />
            <label>Keep me logged in</label>
          </div>
          <a href="#">Forgot password?</a>
        </div>
        <input type="submit" name="login" id="login" value="Log in" />
        <p>
          Don't have an account?<a class="signup-link" href="signup.php">Sign up</a>
        </p>
      </form>
    </div>
  </div>
</body>

</html>