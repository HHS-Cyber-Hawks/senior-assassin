<?php

  include("environment.php");

  $id = $conn->real_escape_string($id);

  $sql = "SELECT user_name, email FROM users WHERE id = $id";

  $name = get_value($sql, "user_name");
  $email = get_value($sql, "email");

  $sql2 = "INSERT INTO players(player_name, email, player_status) VALUES ('$name', '$email', 0)";

  if ($conn->query($sql2) == TRUE)
  {
    //header("Location: index.php?round=" . $round);
    $sql3 = "UPDATE users SET has_paid = 1 WHERE id = $id";
    $conn->query($sql3);
  }
  else
  {
    echo "Error";
  }
