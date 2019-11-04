<?php

extract($_REQUEST);

include("environment.php");

$conn = create_connection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$assignment_id = $id;

// If the status of assignment was set to confirmed, make a new assignment that makes the target the target of the person who was eliminated
if ($status == 2)
{
  echo $assignment_id . "<br />";

  $sql1 = "SELECT attacker_id FROM assignments WHERE assignment_id =" . $assignment_id;
  $attacker = get_value($sql1, "attacker_id");

  $sql2 = "SELECT target_id FROM assignments WHERE assignment_id =" . $assignment_id;
  $old_target_id = get_value($sql2, "target_id");

  $sql3 = "SELECT assignment_id FROM assignments WHERE attacker_id =" . $old_target_id;
  $obsolete_assignment = get_value($sql3, "assignment_id");

  $sql4 = "SELECT target_id FROM assignments WHERE assignment_id =" . $obsolete_assignment;
  $new_target_id = get_value($sql4, "target_id");

  if ($attacker !== $new_target_id)
  {
    $conn->query("INSERT INTO assignments(attacker_id, target_id) VALUES(" . $attacker . ", " . $new_target_id . ")");
  }

  $sql5 = "UPDATE assignments SET assignment_status = 3 WHERE assignment_id=" . $obsolete_assignment;
  $conn->query($sql5);
}

// Makes the table show the new status that was selected
$sql = "UPDATE assignments SET assignment_status = $status WHERE assignment_id=" . $assignment_id;
$conn->query($sql);

// The query to make a player's status "out" on index page
$got_out = "UPDATE players SET player_status = -1 WHERE player_id = ";

// The query for getting the id of the target
$get_target_id = "SELECT target_id FROM assignments WHERE assignment_id =" . $assignment_id;


// DOESNT MAKE NEW ASSIGNMENT



// Sets $target_id equal to the result of the query
$target_id = get_value($get_target_id, "target_id");

// Executes the $got_out query
$conn->query($got_out . $target_id);

$conn->close();

header("Location: assignment_display.php");
