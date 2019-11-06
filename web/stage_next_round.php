<?php

include("environment.php");

$conn = create_connection();

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

// Gets the number of assignments
$sql = "SELECT count(assignment_id) FROM assignments";
$num_assignments = get_value($sql, "count(assignment_id)");

// Makes an array of the assignment ids
$assignment_array = get_value("SELECT assignment_id FROM assignments", "assignment_id");

// Gets the status of the assignment given the assignment id
$get_assignment_status = "SELECT assignment_status FROM assignments WHERE assignment_id = ";

// Gets the player status given the player id
$get_player_status = "SELECT player_status FROM players WHERE player_id = ";

// Gets the assignment id given the target id
$get_id_when_target = "SELECT assignment_id FROM assignments WHERE target_id = ";

// Sets the assignment_status to "3" (obsolete) given the assignement id
$change_to_obsolete = "UPDATE assignments SET assignment_status = 3 WHERE assignment_id = ";

// Gets the attacker id given the assignment id
$get_attacker_id = "SELECT attacker_id FROM assignments WHERE assignment_id = ";

// Sets the player status to "1" (moving on) given the player id
$moving_on = "UPDATE players SET player_status = 1 WHERE player_id = ";

// Sets the player status to "-1" (out) given the player id
$did_not_eliminate = "UPDATE players SET player_status = -1 WHERE player_id = ";

for ($i = 1; $i <= $num_assignments; $i++)
{
  $assignment_id = $i;

  // Sets status equal to the status of the assignment
  $status = get_value($get_assignment_status . $assignment_id, "assignment_status");
  $attacker_id = get_value($get_attacker_id . $assignment_id, "attacker_id");

  if ($status == 2)
  {
    // $as_target_id returns the assignment id of where the current index is the target
    $as_target_id = get_value($get_id_when_target . $attacker_id, "assignment_id");

    // $victim_status returns the status of the attacker when he is a target
    $victim_status = get_value($get_player_status . $as_target_id, "player_status");

    //This if block prevents someone who has been eliminated to moving on to the next round
    if($victim_status != 2)
    {
      $conn->query($moving_on . $attacker_id);
    }
  }
  else
  {
    $player_status = get_value($get_player_status . $attacker_id, "player_status");
    if ($player_status != 1)
    {
      $conn->query($did_not_eliminate . $attacker_id);
    }
    $conn->query($change_to_obsolete . $assignment_id);
  }
}

header("Location: assignment_display.php");
