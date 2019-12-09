<?php
  // PLAYER STATUS KEY
  # -1  = out
  # 0 = playing
  # 1 = can move on

  // ASSIGNMENT STATUS KEY
  # 0 = open
  # 1 = pending
  # 3 = confirmed
  # 4 = obsolete

  //Set info on user session (player is admin?)
  session_start();
  $_SESSION["admin"] = true;

  function isAdmin()
  {
    return $_SESSION["admin"];
  }

  function create_connection()
  {
    $servername = "mysql.server295.com";
    $username = "assassin";
    $password = "billiard gale seeing";
    $dbname = "passingf_assassin";

    // Create connection
    return new mysqli($servername, $username, $password, $dbname);
  }

  // for parameters you pass in the actual query you want int SQL and value is the actual value you are selecting
  function get_value($query, $value) {
    $conn = create_connection();
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row["$value"];
  }

  $CURRENT_ROUND = 1;
 ?>
