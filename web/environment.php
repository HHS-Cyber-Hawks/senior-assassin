<?php
  function create_connection() {
    $servername = "mysql.server295.com";
    $username = "assassin";
    $password = "billiard gale seeing";
    $dbname = "passingf_assassin";

    // Create connection
    return new mysqli($servername, $username, $password, $dbname);
  }
 ?>
