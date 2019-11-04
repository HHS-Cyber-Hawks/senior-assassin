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
$get_id_when_target = "SELECT assignment_id FROM assignments WHERE target_id = ";
$remove_assignment = "DELETE FROM assignments WHERE assignment_id = ";
$change_to_obsolete = "UPDATE assignments SET assignment_status = 3 WHERE assignment_id = ";
$get_attacker_id = "SELECT attacker_id FROM assignments WHERE assignment_id = ";
$moving_on = "UPDATE players SET player_status = 1 WHERE player_id = ";
$did_not_eliminate = "UPDATE players SET player_status = -1 WHERE player_id = ";

for ($i = 1; $i <= $num_assignments; $i++)
{
  $assignment_id = $i;

  $result = $conn->query($get_status . $assignment_id);
  $row = $result->fetch_assoc();
  $status = $row["assignment_status"];

  $result = $conn->query($get_attacker_id . $assignment_id);
  $row = $result->fetch_assoc();
  $attacker_id = $row["attacker_id"];

  if ($status == 2)
  {
    // $as_target_id returns the assignment id of where the current index is the target
    $result = $conn->query($get_id_when_target . $attacker_id);
    $row = $result->fetch_assoc();
    $as_target_id = $row["assignment_id"];
    //TODO Figure out error here, I believe "assignment_id" that is passed in on the last line

    // $victim_status returns the status of the attacker when he is a target
    $result = $conn->query($get_status . $as_target_id);
    $row = $result->fetch_assoc();
    $victim_status = $row["assignment_status"];

    //This if block prevents someone who has been eliminated to moving on to the next round
    if($victim_status != 2)
    {
      //I changed from instead of removing the assignment ID to instead changing the status to obsolete.
      // $conn->query($remove_assignment . $assignment_id);
      //$conn->query($change_to_obsolete . $assignment_id);
      $conn->query($moving_on . $attacker_id);
    }
  }
  else
  {
    $conn->query($did_not_eliminate . $attacker_id);
    $conn->query($change_to_obsolete . $assignment_id);
  }
}

header("Location: assignment_display.php");




// ONE QUERY FOR ARRAY OF ALL STATUSES TO MAKE IT FASTER
