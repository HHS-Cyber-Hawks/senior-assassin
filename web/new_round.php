<?php

include("environment.php");

$conn = create_connection();

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT count(assignment_id) from assignments";
$num_assignments = get_value($sql, "count(assignment_id)");

// Gets the status of the assignement given the assignment id
$get_assignment_status = "SELECT assignment_status FROM assignments WHERE assignment_id = ";

// Gets the player status given the player id
$get_player_status = "SELECT player_status FROM players WHERE player_id = ";

// Gets the assignement id given the target id
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

  $status = get_value($get_assignment_status . $assignment_id, "assignment_status");
  $attacker_id = get_value($get_attacker_id . $assignment_id, "attacker_id");

  if ($status == 2)
  {
    // $as_target_id returns the assignment id of where the current index is the target
<<<<<<< HEAD
    $as_target_id = get_value($get_id_when_target . $attacker_id, "assignment_id");
    //TODO Figure out error here, I believe "assignment_id" that is passed in on the last line
=======
    $result = $conn->query($get_id_when_target . $attacker_id);
    $row = $result->fetch_assoc();
    $as_target_id = $row["assignment_id"];
    //Figure out error here, I believe "assignment_id" that is passed in on the last line
>>>>>>> febea57d61ccf148f3101a20777daa13456ad4f4

    // $victim_status returns the status of the attacker when he is a target
    $victim_status = get_value($get_player_status . $as_target_id, "player_status");

    //This if block prevents someone who has been eliminated to moving on to the next round
    if($victim_status != 2)
    {
      echo "SOMEONE MOVING ON <br />";
      // I changed from instead of removing the assignment ID to instead changing the status to obsolete.
      // $conn->query($change_to_obsolete . $assignment_id);
      $conn->query($moving_on . $attacker_id);
    }
  }
  else
  {
    $conn->query($did_not_eliminate . $attacker_id);
    $conn->query($change_to_obsolete . $assignment_id);
  }
}

 // header("Location: assignment_display.php");
