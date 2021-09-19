<?php
  // Include config file
  require_once "config.php";

  //initialize the session
  session_start();

  //Check if the user is logged in, if not then direct to login page

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo '<script> location.replace("login.php"); </script>';
  exit;
  }
?>
<!doctype html>
<html lang="en">
  <!-- include header -->
  <head>
    <title>Culture Matcher - Test</title>
    <meta name="description" content="Discover your culture now">
    <meta name="keywords" content="">
  </head>
  <?php  require_once "header.php" ?>

  <!-- include navbar -->
  <?php  require_once "navbar_internal.php" ?>

  <body>

  <!-- HEADER
  ================================================== -->
  <header class="bg-dark pt-9 pb-11 d-none d-md-block">
    <div class="container-md">
      <div class="row align-items-center">
        <div class="col">

          <!-- Heading -->
          <h1 class="font-weight-bold text-white mb-2">
            Culture Test
          </h1>

          <!-- Text -->
          <p class="font-size-lg text-white-75 mb-0">
            Have fun!
          </p>

        </div>
      </div> <!-- / .row -->
    </div> <!-- / .container -->
  </header>

  <main class="pb-8 pb-md-11 mt-md-n6">
    <div class="container-md">





        <div class="col-12 col-md-9">

          <!-- Card -->
          <div class="card card-bleed shadow-light-lg">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col">
                  <?php require_once "./pages/test/survey_questions.php"; ?>

                </div>
              </div>
            </div>

          </div>

        </div>
    </div> <!-- / .container -->
  </main>
  </body>
  <!-- include footer -->
  <?php  require_once "footer.php" ?>

  <!-- include javascript documentation -->
  <?php  require_once "js.php" ?>
</html>
