<?php
session_start();
if (isset($_SESSION["customer"])) {
   header("Location:customer/index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign up</title>
    <link rel="stylesheet" href="css/general.css"/>
  </head>
  <body>
    <div id="sign-account-page-wrapper">
      <div class="sign-account-bg">
        <img class="sign-account-bg-img" src="images/log-in/signup-bg.jpg" />
      </div>
      <div class="form-wrapper">
      <!-- registring an account -->
      <?php
        if (isset($_POST["submit"])) {
           $fullName = $_POST["customer_name"];
           $email = $_POST["customer_email"];
           $shipping_address = $_POST["customer_address"];
           $password = $_POST["customer_password"];
           $passwordRepeat = $_POST["customer_confirm_password"];
           //encrypt password
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);
           $errors = array(); //variable for error message
           
           if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
            if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           if ($password!==$passwordRepeat) {
            array_push($errors,"Password does not match");
           }
           require_once "database.php";
           $sql = "SELECT * FROM customers WHERE customer_email = '$email'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<script>alert($error)</script>";
            }
           }else{
            
            $sql = "INSERT INTO customers (customer_name, customer_email, customer_address, customer_password) VALUES ( ?, ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"ssss",$fullName, $email, $shipping_address, $passwordHash);
                mysqli_stmt_execute($stmt);
                echo "<script>alert('You are registered successfully.')</script>";
            }else{
                die("Something went wrong");
            }
           }
          

        }
        ?>
      <!-- form for registration -->
        <form action="signup.php" method="post">
          <h1>Sign Up</h1>
          <p>Please enter your details to sign up.</p>
          <input
            type="text"
            id="customer_name"
            name="customer_name"
            placeholder="Full Name"
            required pattern="[a-zA-Z'-'\s]*"
          />
          <input
            type="email"
            id="customer_email"
            name="customer_email"
            placeholder="Email"
            required
          />
          <input
            type="text"
            id="customer_address"
            name="customer_address"
            placeholder="Address"
            required
          />
          <input
            type="password"
            id="customer_password"
            name="customer_password"
            placeholder="Password"
            required
          />
          <input
            type="password"
            id="customer_confirm_password"
            name="customer_confirm_password"
            placeholder="Confirm Password"
            required
          />
          <div class="checkboxAndForgotPass-wrapper">
            <div class="checkbox-wrapper">
              <input
                type="checkbox"
                id="Terms-and-policy"
                name="Terms-and-policy"
                value="#"
                required
              />
              <label>I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a></label>
            </div>
          </div>
          <div class="submit">
            <input type="submit" name="submit" id="submit" value="Sign up" />
          </div>
          <p>
            Already a member?<a class="signup-link" href="index.php"
              >Log in</a
            >
          </p>
        </form>
      </div>
    </div>
  </body>
</html>