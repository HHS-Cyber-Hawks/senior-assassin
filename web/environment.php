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

<<<<<<< HEAD
  function isAdmin()
  {
    return $_SESSION["admin"];
  }

  function create_connection()
  {
=======
  function create_connection() {
>>>>>>> 0e291f96326367acf0a004f23ef81273862087f9
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

  function verify_authentication()
  {
      global $config, $content, $unauthenticatedContents, $unauthenticatedScripts;

      $scriptRoot = $config['general']['script_root'];
      $scriptName = substr($_SERVER['PHP_SELF'], strlen($scriptRoot));

      if (!(($scriptName == 'index.php' && in_array($content, $unauthenticatedContents)) ||
            in_array($scriptName, $unauthenticatedScripts)))
      {
          session_start();

          if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated'])
          {
              session_unset();
              session_destroy();

              header('Location: index.php');

              exit();
          }

          session_write_close();
      }
    }


 ?>
