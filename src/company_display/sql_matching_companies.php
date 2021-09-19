<?php



  // grab the user_companies table
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
