<?php
  // Include config file
  require_once "config.php";

  // Initialize the sesssion
  session_start();
  $username = $_SESSION["username"];

  //Check if the user is logged in, if not then redirect to login page

  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
  }

  //-----Check if user has taken test and table created-----//


  $stmt = $pdo->prepare("SELECT taken_test, company_table_created FROM users WHERE username = :username");
  //Bind variables to the prepared statement as parameters
  $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

  // Set parameters
  $param_username = $username;

  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result['taken_test']) {
    $taken_test = true;
  } else {
    $taken_test = false;
  }
  if ($taken_test === false){
    echo '<script> location.replace("welcome.php"); </script>';
  }

  if ($result['company_table_created']) {
    $company_table_created = true;

  } else {
    $company_table_created = false;
  }

  unset($stmt);

  //Grab table name

  $table_name = preg_replace('/@/','_',$username); //regex to remove invalid characters for table
  $table_name = preg_replace('/\./','_',$table_name);
  $table_name .= "_companies";

  if ($company_table_created) {
    echo "";
  } else {

    //create table to capture user's scores
    try {

      //sql to create table
      $sql = "CREATE TABLE " . $table_name . " (
      id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      company_name VARCHAR(256),
      match_score FLOAT(4),
      clicked_on_header INT(3),
      clicked_on_about_link INT(3),
      clicked_on_careers_link INT(3),
      bookmarked BOOLEAN NOT NULL DEFAULT FALSE
      )";

      // use exec() since no results are returned
      $pdo_user_companies->exec($sql);
      unset($sql);
    } catch (exception $e){
      echo "";
    }
  }




?>
<!doctype html>
<html lang="en">
  <!-- include header -->
  <head>
    <title>Culture Matcher - Bookmarked Companies</title>
    <meta name="description" content="This page contains the list of companies that you have bookmarked">
    <meta name="keywords" content="">
  </head>
  <?php  require_once "header.php" ?>

  <body>
    <!-- include navbar -->
    <?php  require_once "navbar_internal.php" ?>

    <header class="bg-dark pt-9 pb-11 d-none d-md-block">
      <div class="container-md">
        <div class="row align-items-center">
          <div class="col">

            <!-- Heading -->
            <h1 class="font-weight-bold text-white mb-2">
              Bookmarked Companies
            </h1>

            <!-- Text -->
            <p class="font-size-lg text-white-75 mb-0">

            </p>

          </div>
        </div> <!-- / .row -->
      </div> <!-- / .container -->
    </header>
    <main class="pb-8 pb-md-11 mt-md-n6">
      <div class="container-md">
        <div class="card shadow-light-lg accordion mb-5 mb-md-6" id="helpAccordionOne">
          <div class="list-group">

            <!--- insert company matches here -->
            <?php
              require "./pages/bookmarks/bookmark_company_list.php";
            ?>
            <!--- end company matches -->
          </div>
        </div>
      </div>
    </main>


    <!-- include footer -->
    <?php  require_once "footer.php" ?>

    <!-- include javascript documentation -->
    <?php  require_once "js.php" ?>

  </body>
</html>
