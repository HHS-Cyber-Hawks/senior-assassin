<?php

extract($_REQUEST);

include("environment.php");

$conn = create_connection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $conn->real_escape_string($id);
$status = $conn->real_escape_string($status);
$round = $conn->real_escape_string($round);

$assignment_id = $id;

// If the status of assignment was set to confirmed, make a new assignment that makes the target the target of the person who was eliminated
if ($status == 2)
{
  // The query to get the attacker id given the assignment_id
  $sql1 = "SELECT attacker_id FROM assignments WHERE assignment_id = $assignment_id AND assignment_round = $round";
  $attacker = get_value($sql1, "attacker_id");
  echo "ATTACKER: $attacker <br />";

  // The query to get the target id from the original assignment whose status is being changed
  $sql2 = "SELECT target_id FROM assignments WHERE assignment_id = $assignment_id";
  $old_target_id = get_value($sql2, "target_id");
  echo "OLD TARGET ID: $old_target_id <br />";

  // The query to get the assignment id of the old assignemnt that will become obsolete
  $sql3 = "SELECT assignment_id FROM assignments WHERE attacker_id = $old_target_id AND assignment_round = $round AND assignment_status != 2";
  $obsolete_assignment = get_value($sql3, "assignment_id");
  echo "OBSOLETE ASSIGNMENT: $obsolete_assignment <br />";

  // The query to get the target id for the new assignemnt
  $sql4 = "SELECT target_id FROM assignments WHERE assignment_id = $obsolete_assignment AND assignment_round = $round";
  $new_target_id = get_value($sql4, "target_id");
  echo "NEW TARGET ID: $new_target_id <br />";

  // Gets the status of the old assignment
  $sql = "SELECT assignment_status FROM assignments WHERE assignment_id = $obsolete_assignment";
  $status_of_old_assignment = get_value($sql, "assignment_status");
  echo "STATUS OF OLD ASSIGNMENT: $status_of_old_assignment <br />";

  // Checks to make sure that the new assignment is not a person attacking themselves
  if ($attacker != $new_target_id)
  {
    // Makes the new assignment
    $sql = "INSERT INTO assignments(attacker_id, target_id, assignment_round) VALUES($attacker, $new_target_id, $round)";
    $conn->query($sql);
  }

  // Makes the old assignment obsolete if it was not already confirmed
  if ($status_of_old_assignment != 2)
  {
      $sql5 = "UPDATE assignments SET assignment_status = 3 WHERE assignment_id = $obsolete_assignment";
      $conn->query($sql5);
  }

  // The query to show that a player has gotten at least one of their targets out
  $can_move_on = "UPDATE players SET player_status = 1 WHERE player_id = ";

  // The query for getting the id of the target
  $get_target_id = "SELECT target_id FROM assignments WHERE assignment_id = $assignment_id AND assignment_round = $round";

  // Sets $target_id equal to the result of the query
  $target_id = get_value($get_target_id, "target_id");

  // The query to make a player's status "out" on index page
  $got_out = "UPDATE players SET player_status = -1 WHERE player_id = ";

  // Executes the $got_out query
  $conn->query($got_out . $target_id);

  // Executes the $can_move_on query
  $conn->query($can_move_on . $attacker);
}

// Makes the table show the new status that was selected
$sql = "UPDATE assignments SET assignment_status = $status WHERE assignment_id = $assignment_id AND assignment_round = $round";
$conn->query($sql);

// Gets the current round and brings the admin back to the correct page
$get_round = "SELECT assignment_round FROM assignments WHERE assignment_id = $assignment_id";
$round = get_value($get_round, "assignment_round");

$conn->close();

header("Location: assignment_display.php?round=" . $round);
