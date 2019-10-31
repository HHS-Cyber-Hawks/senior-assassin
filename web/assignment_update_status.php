<?php

extract($_REQUEST);

include("environment.php");

$conn = create_connection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE assignments SET assignment_status = $status WHERE assignment_id=" . $id;

if ($conn->query($sql) === TRUE)
{
  header("Location: assignment_display.php");
}

$conn->close();

echo $id . " " . $status;

?>
