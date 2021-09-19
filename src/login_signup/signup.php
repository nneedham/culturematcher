<?php

?>
<!doctype html>
<html lang="en">
  <!-- include header -->
  <head>
    <title>Culture Matcher - Sign Up</title>
    <meta name="description" content="Sign up here for Culture Matcher">
    <meta name="keywords" content="">
  </head>
  <?php  require_once "header.php" ?>

  <body>
    <!-- include navbar -->
    <?php  require_once "navbar_external.php" ?>

    <?php
    // Include config file
    require_once "config.php";

    // Define variables and initialize with empty values
    $username = $full_name = $password = $confirm_password = "";
    $username_err = $full_name_err = $password_err = $confirm_password_err = "";

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

      // Validate email
      if(empty(trim($_POST["username"])) ){
        $username_err = "Please enter your email.";
      } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)){
          //Bind variables to the prepared statement as parameters
          $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

          // Set parameters
          $param_username = trim($_POST["username"]);

          // Attempt to execute the prepared statement
          if($stmt->execute()){
            if($stmt->rowCount()==1){
              $username_err = "This username is already taken.";
            } else{
              $username = trim($_POST["username"]);
            }
          } else {
            echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          unset($stmt);
        }
      }

      //Valdidate password
      if(empty(trim(trim($_POST["password"])))){
        $password_err = "Please enter a password.";
      } elseif(strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
      } else{
        $password = trim($_POST["password"]);
      }

      //Validate confirm password
      if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
      } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
          $confirm_password_err = "Password did not match.";
        }
      }

      // Validate full name
      if(empty(trim($_POST["full_name"])) ){
        $full_name_err = "Please enter your name.";
      } else {
        $full_name = trim($_POST["full_name"]);
      }
      // Check input errors before inserting in database
      if(empty($username_err) && empty($full_name_err) && empty($password_err) && empty($confirm_password_err)){

        //Prepare an insert statement
        $sql = "INSERT INTO users (username, password, full_name, email) VALUES (:username, :password, :full_name, :email)";

        if($stmt = $pdo->prepare($sql)){
          //Bind variables to the prepared statement as parameters
          $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
          $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
          $stmt->bindParam(":full_name", $param_full_name, PDO::PARAM_STR);
          $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

          // Set parameters
          $param_username = $username;
          $param_email = $username;
          $param_full_name = trim($_POST["full_name"]);
          $param_password = password_hash($password, PASSWORD_DEFAULT); // creates a password hash

          //Attempt to executre the prepared statement
          if($stmt->execute()){
            // Password is correct, so start a new session
            session_start();

            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            // Redirect to login page
            header("location: welcome.php");
          } else{
            echo "Something went wrong. Please try again later.";
          }

          //Close statement
          unset($stmt);
        }
      }
      // CLose connection
      unset($pdo);
    }
    ?>

                <div class="container d-flex flex-column">

                  
                    <div class="card-body">

                      <!-- Heading -->
                      <h2 class="mb-0 font-weight-bold text-center" id="modalSignupHorizontalTitle">
                        Sign Up
                      </h2>

                      <!-- Text -->
                      <p class="mb-6 text-center text-muted">
                        Find your fit in minutes.
                      </p>

                      <!-- Form -->
                      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">

                        <!-- Email -->
                          <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="username" class="form-control" value="<?php echo $username; ?>" placeholder="name@address.com">
                            <span class="help-block"><?php echo $username_err; ?></span>
                          </div>

                          <!-- Name -->
                          <div class="mb-3">
                            <label>Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo $full_name; ?>" placeholder="Name">
                            <span class="help-block"><?php echo $full_name_err; ?></span>
                          </div>

                          <!-- Password -->
                          <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Enter your password">
                            <span class="help-block"><?php echo $password_err; ?></span>
                          </div>
                          <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Confirm your password">
                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                          </div>

                          <!-- Submit -->
                          <button class="btn btn-block btn-primary" type="submit">
                            Sign up
                          </button>

                        </form>

                        <!-- Text -->
                        <p class="mb-0 font-size-sm text-center text-muted">
                          Already have an account? <a href="login.php">Log in</a>.
                        </p>

                      </div>

                    </div>
                  </div>

                </div> <!-- / .row -->
              </div>


    <!-- include footer -->
    <?php  require_once "footer.php" ?>

    <!-- include javascript documentation -->
    <?php  require_once "js.php" ?>

  </body>
</html>
