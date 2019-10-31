<?php

include("environment.php");

$conn = create_connection();

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT count(assignment_id) from assignments";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$num_assignments = $row["count(assignment_id)"];

$get_status = "SELECT assignment_status FROM assignments WHERE assignment_id = ";
$remove_assignment = "DELETE FROM assignments WHERE assignment_id = ";
$get_attacker_id = "SELECT attacker_id FROM assignments WHERE assignment_id = ";
$moving_on = "UPDATE players SET player_status = 1 WHERE player_id = ";

for ($i = 1; $i <= $num_assignments; $i++)
{
  $assignment_id = $i;

  $result = $conn->query($get_status . $assignment_id);
  $row = $result->fetch_assoc();
  $status = $row["assignment_status"];

  if ($status == 2)
  {
    $result = $conn->query($get_attacker_id . $assignment_id);
    $row = $result->fetch_assoc();
    $attacker_id = $row["attacker_id"];

    $conn->query($remove_assignment . $assignment_id);
    $conn->query($moving_on . $attacker_id);
  }
}

header("Location: assignment_display.php");




// ONE QUERY FOR ARRAY OF ALL STATUSES TO MAKE IT FASTER
