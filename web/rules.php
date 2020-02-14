<?php
include("environment.php");

extract($_REQUEST);

// Create connection
$conn = create_connection();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($submit)) {



?>

<html>
  <head>
    <title>Rules</title>
    <h1 class="header">Rules</h1>
    <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
  </head>
<?php
  echo"<form action='rules.php' method='post' style='margin-bottom: 5px;'>" .
      "<textarea rows='4' cols='50' type='text' name='rule' value='" . $row['rule'] . "'></textarea>" .
      "<br />" .
      "<button class ='button' type='submit' name='submit'>Save</button>" .
  "</form>"
?>
<?php

}
else
{
  $rule = $conn->real_escape_string($rule);

  $sql = "INSERT INTO rules (rule) VALUES ('$rule')";

  if ($conn->query($sql) === TRUE)
  {
    header("Location: index.php");
  }
  else
  {
    echo "Error";
  }
}
?>












</html>
