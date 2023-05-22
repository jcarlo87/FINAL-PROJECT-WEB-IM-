<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php"); //the home page
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css" />
    <style>
      .alert-danger {
        color: #851923;
        padding: 4px 6px;
        text-align: center;
        border-radius: 4px;
        background: #ffe3e5;
        border: 1px solid #dfa4ab;
        margin: 15px 30px 0;
      }

      .alert-success{
        color: #198519;
        padding: 4px 6px;
        text-align: center;
        border-radius: 4px;
        background: #e3ffe3;
        border: 1px solid #a8dfa4;
        margin: 15px 30px 0;
      }
    </style>
  </head>
  <body>
    <div id="sign-account-page-wrapper">
      <!-- login page background-image -->
      <div class="sign-account-bg">
        <img class="sign-account-bg-img" src="images/log-in/login-bg.jpg" />
      </div>
      <div class="form-wrapper">
        <!-- status message (where the warining message is displayed) -->
        <?php
        if (isset($_POST["login"])) {
           $email = $_POST["customer_email"];
           $password = $_POST["customer_password"];
            require_once "database.php";
            $sql = "SELECT * FROM customers WHERE customer_email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["customer_password"])) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    header("Location: index.php");
                    die();
                }else{
                    echo "<div class='alert-danger'>Password does not match</div>";
                }
            }else{
                echo "<div class='alert-danger'>Email does not match</div>";
            }
        }
        ?>

        <!-- login form -->
        <form action="login.php" method="post">
          <h1>Login</h1>
          <p>Please enter your login details to sign in.</p>
          <input
            type="email"
            id="customer_email"
            name="customer_email"
            placeholder="Email Address"
            required
          />

          <input
            type="password"
            id="customer_password"
            name="customer_password"
            placeholder="Password"
            required
          />

          <div class="checkboxAndForgotPass-wrapper">
            <div class="checkbox-wrapper">
              <input
                type="checkbox"
                id="keep-me-login"
                name="keep-me-login"
                value="#"
              />
              <label>Keep me logged in</label>
            </div>
            <a href="#">Forgot password?</a>
          </div>
          <input type="submit" name="login" id="login" value="Log in" />
          <p>
            Don't have an account?<a class="signup-link" href="signup.php"
              >Sign up</a
            >
          </p>
        </form>
      </div>
    </div>
  </body>
</html>