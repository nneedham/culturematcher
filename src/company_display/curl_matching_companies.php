<?php

//grab scores for company matching
$sql = "SELECT username, innovator_score, paragon_score, trendsetter_score, citizen_score, athlete_score, tinkerer_score, steward_score FROM user_scores WHERE username = :username";

if($stmt = $pdo->prepare($sql)){
    //Bind variables to the prepared statement as parameters
    $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

    // Set parameters
    $param_username = $username;

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Grab scores for later
    $innovator_score = $result['innovator_score'];
    $paragon_score = $result['paragon_score'];
    $trendsetter_score = $result['trendsetter_score'];
    $citizen_score = $result['citizen_score'];
    $athlete_score = $result['athlete_score'];
    $tinkerer_score = $result['tinkerer_score'];
    $steward_score = $result['steward_score'];

  }

  //create and initialize a curl session
  $curl = curl_init();

  // import individual culture scores
  // see matching_companies.php for score import

  $api_url = /* API URL goes here */. $innovator_score ."&paragon_score=".$paragon_score . "&trendsetter_score=" . $trendsetter_score . "&citizen_score=" . $citizen_score . "&athlete_score=" . $athlete_score . "&tinkerer_score=" . $tinkerer_score . "&steward_score=" . $steward_score ."";

  // set up our url with curl_setopt()
  curl_setopt($curl, CURLOPT_URL, $api_url);

  //return the transfer as a string, also with setopt()
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

  //curl_exec() exectues the started curl session
  // $output contains the output string
  $output = curl_exec($curl);
  $output = json_decode($output, true);


  for ($x = 0, $size = count($output); $x < $size; ++$x) {
    $company_name = $output[$x]["Company Name"];
    $match_score = $output[$x]["match_score"];
    $match_score = round($match_score, 1);
    $statement = $pdo->query("SELECT * FROM `cc_cleaned_2` WHERE company_name='$company_name'");
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    //Update company table
    // Prepare an insert statement
    $sql = "INSERT INTO " . $table_name . " (company_name, match_score) VALUES (:company_name, :match_score)";

    if($stmt = $pdo_user_companies->prepare($sql)){
      //Bind variables to the perpared statement as parameters
      $stmt->bindParam(":company_name", $param_company_name, PDO::PARAM_STR);
      $stmt->bindParam(":match_score", $param_match_score, PDO::PARAM_STR);

      //set parameters
      $param_company_name = $company_name;
      $param_match_score = $match_score;

      //execute sql
      if($stmt->execute()){

      } else{
        echo "Something went wrong. Please try again later.";
      }

    }

  }

  //update user company table created & company table last update
  //prepare sql
  $stmt = $pdo->prepare("UPDATE users SET company_table_created = 1 WHERE username = :username");

  //Bind variables to the prepared statement as parameters
  $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);


  // Set parameters
  $param_username = $username;


  //execute sql
  if($stmt->execute()){
    //Redirect to login page
    echo '<script> location.replace("welcome.php"); </script>';
  } else{
    echo "Something went wrong. Please try again later.";
  }


  //close curl resource to free up system resources
  // (deletes the variable made by curl_init)
  curl_close($curl);

  // now build the user_companies table
  $sql_stmt = 'SELECT * FROM ' . $table_name;
  $stmt_user_companies = $pdo_user_companies->query($sql_stmt);

  $results_user_companies = $stmt_user_companies->fetchAll(PDO::FETCH_ASSOC);
  for ($x = 0, $size = count($results_user_companies); $x < $size; ++$x) {
    $company_id = $results_user_companies[$x]["id"];
    $company_name = $results_user_companies[$x]["company_name"];
    $bookmark_bool = $results_user_companies[$x]["bookmarked"];
    $match_score = $results_user_companies[$x]["match_score"];
    $match_score = round($match_score, 1);
    $statement = $pdo->query("SELECT * FROM `cc_cleaned_2` WHERE company_name='$company_name'");
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    require "./pages/matchingcompanies/company_header.php";
  }
?>
