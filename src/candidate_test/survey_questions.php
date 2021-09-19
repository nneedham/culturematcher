<?php
  // Include config file
  require_once "config.php";

  // Check if the user has taken the test
  $username = $_SESSION["username"];

//Check if user has taken test
  $username = $_SESSION["username"];

  $stmt = $pdo->prepare("SELECT taken_test FROM users WHERE username = :username");
  //Bind variables to the prepared statement as parameters
  $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

  // Set parameters
  $param_username = $username;

  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result['taken_test']) {
    echo '<script> location.replace("welcome.php"); </script>';
  }

  unset($stmt);

// pull in questions
  $stmt = $pdo->prepare("SELECT * FROM identity_test");
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  unset($stmt);


  //create table to capture user's scores
  try {
    //regex to remove invalid characters for table
    $table_name = preg_replace('/@/','_',$username);
    $table_name = preg_replace('/\./','_',$table_name);
    $table_name .= "_test";

    //sql to create table
    $sql = "CREATE TABLE " . $table_name . " (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_number INT(6),
    answer VARCHAR(30)
    )";

    // use exec() since no results are returned
    $pdo_user_tests->exec($sql);
    unset($sql);
  } catch (exception $e){
    echo "";
  }

  // On submit, update users_test
  if($_SERVER["REQUEST_METHOD"] == "POST"){

    $test_responses = $_POST;

    for($i = 1; $i < count($test_responses); $i++){
      // Prepare an insert statement
      $sql = "INSERT INTO " . $table_name . " (question_number, answer) VALUES (:question_number, :answer)";

      if($stmt = $pdo_user_tests->prepare($sql)){
        //Bind variables to the perpared statement as parameters
        $stmt->bindParam(":question_number", $param_question_number, PDO::PARAM_INT);
        $stmt->bindParam(":answer", $param_answer, PDO::PARAM_STR);

        //set parameters
        $param_question_number = $i;
        $param_answer = $test_responses[$i];

        //execute sql
        if($stmt->execute()){

        } else{
          echo "Something went wrong. Please try again later.";
        }

      }
    }
  }

  // on submit, update top_identity
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //collect scores
    $innovator_score = 0;
    $paragon_score = 0;
    $trendsetter_score = 0;
    $citizen_score = 0;
    $athlete_score = 0;
    $tinkerer_score = 0;
    $steward_score = 0;

    $test_responses = $_POST;

    //iterate through to count all scores
    for($i = 1; $i < count($test_responses); $i++){
      if($test_responses[$i] === "innovator") {
        $innovator_score ++;
      }
      if($test_responses[$i] === "paragon") {
        $paragon_score ++;
      }
      if($test_responses[$i] === "trendsetter") {
        $trendsetter_score ++;
      }
      if($test_responses[$i] === "citizen") {
        $citizen_score ++;
      }
      if($test_responses[$i] === "athlete") {
        $athlete_score ++;
      }
      if($test_responses[$i] === "tinkerer") {
        $tinkerer_score ++;
      }
      if($test_responses[$i] === "steward") {
        $steward_score ++;
      }
    }

    // capture scores in an array
    $identities = array("innovator"=>$innovator_score,
                    "paragon"=>$paragon_score,
                    "trendsetter"=>$trendsetter_score,
                    "citizen"=>$citizen_score,
                    "athlete"=>$athlete_score,
                    "tinkerer"=>$tinkerer_score,
                    "steward"=>$steward_score,
    );
    //calculate top identity score
    $top_identity = array_search(max($identities),$identities);

    //update top_identity
    $stmt = $pdo->prepare("UPDATE users SET top_identity = :top_identity WHERE username = :username");

    //Bind variables to the prepared statement as parameters
    $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
    $stmt->bindParam(":top_identity", $param_top_identity, PDO::PARAM_STR);

    // Set parameters
    $param_username = $username;
    $param_top_identity = $top_identity;

    //execute sql
    if($stmt->execute()){
      // Redirect to login page
      //echo '<script> location.replace("welcome.php"); </script>';
    } else{
      echo "Something went wrong. Please try again later.";
    }
  }

  // On Submit, save scores to user_scores
  if($_SERVER["REQUEST_METHOD"] == "POST"){
      // Prepare a select statement
      $sql = "INSERT INTO user_scores (username, taken_test, innovator_score, paragon_score, trendsetter_score, citizen_score, athlete_score, tinkerer_score, steward_score) VALUES (:username, :taken_test, :innovator_score, :paragon_score, :trendsetter_score, :citizen_score, :athlete_score, :tinkerer_score, :steward_score)";

      if($stmt = $pdo->prepare($sql)){
        //Bind variables to the perpared statement as parameters
        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        $stmt->bindParam(":taken_test", $param_taken_test, PDO::PARAM_BOOL);
        $stmt->bindParam(":innovator_score", $param_innovator_score, PDO::PARAM_INT);
        $stmt->bindParam(":paragon_score", $param_paragon_score, PDO::PARAM_INT);
        $stmt->bindParam(":trendsetter_score", $param_trendsetter_score, PDO::PARAM_INT);
        $stmt->bindParam(":citizen_score", $param_citizen_score, PDO::PARAM_INT);
        $stmt->bindParam(":athlete_score", $param_athlete_score, PDO::PARAM_INT);
        $stmt->bindParam(":tinkerer_score", $param_tinkerer_score, PDO::PARAM_INT);
        $stmt->bindParam(":steward_score", $param_steward_score, PDO::PARAM_INT);

        //Set parameters
        $param_username = $_SESSION["username"];
        $param_taken_test = true;
        $param_innovator_score = $innovator_score;
        $param_paragon_score = $paragon_score;
        $param_trendsetter_score = $trendsetter_score;
        $param_citizen_score = $citizen_score;
        $param_athlete_score = $athlete_score;
        $param_tinkerer_score = $tinkerer_score;
        $param_steward_score = $steward_score;

        //Attempt to execute the prepared statement
        if($stmt->execute()){

        } else{
          echo "Something went wrong. Please try again later.";
        }
        //Close statement
        unset($stmt);
      }
    }


  // On submit, update taken_test
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //update taken_test
    $stmt = $pdo->prepare("UPDATE users SET taken_test = 1 WHERE username = :username");
    //Bind variables to the prepared statement as parameters
    $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

    // Set parameters
    $param_username = $username;

    //execute sql
    if($stmt->execute()){
      // Redirect to login page
      echo '<script> location.replace("welcome.php"); </script>';
    } else{
      echo "Something went wrong. Please try again later.";
    }
  }


?>


    <div class="container-fluid">
      <div class="row">

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <?php
                for ($x = 0, $size = count($result); $x < $size; ++$x) {
                  $question_number = $result[$x]['question_number'];
                  $question_prompt = $result[$x]['question'];
                  $innovator_answer = $result[$x]['innovator'];
                  $paragon_answer = $result[$x]['paragon'];
                  $trendsetter_answer = $result[$x]['trendsetter'];
                  $citizen_answer = $result[$x]['citizen'];
                  $athlete_answer = $result[$x]['athlete'];
                  $tinkerer_answer = $result[$x]['tinkerer'];
                  $steward_answer = $result[$x]['steward'];
                  require "./pages/test/test_list.php";
                }
              ?>
              <input class="btn btn-primary btn-lg" type="submit" name="button1" class="button" value="Submit"></button>
            </form>
</div>
