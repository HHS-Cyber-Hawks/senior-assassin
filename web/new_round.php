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

    // get_value($get_id_when_target . $attacker_id, "assignment_id");
    //Figure out error here, I believe "assignment_id" that is passed in on the last line

    // $victim_status returns the status of the attacker when he is a target
    $result = $conn->query($get_status . $as_target_id);
    $row = $result->fetch_assoc();
    $victim_status = $row["assignment_status"];

    //This if block prevents someone who has been eliminated to moving on to the next round
    if($victim_status != 2)
    {
      //I changed from instead of removing the assignment ID to instead changing the status to obsolete.
      // $conn->query($remove_assignment . $assignment_id);
      //$conn->query($chang e_to_obsolete . $assignment_id);
      $conn->query($moving_on . $attacker_id);
    }
  }
  else
  {
    $conn->query($did_not_eliminate . $attacker_id);
    $conn->query($change_to_obsolete . $assignment_id);
  }
}

// returns the number of players
$sql = "SELECT count(player_id) from players";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$num_players = $row["count(player_id)"];

// initializes players moving on array which will be a pool of players in the next round
$players_moving_on = array();

for($i = 1; $i <= $num_players; $i++)
{
  //gets the value to see if the player is actually going to move on
  $sql = "SELECT player_status FROM players WHERE player_id =" . $i;
  $current_player_status = get_value($sql, "player_status");

  if($current_player_status == 1)
  {
    array_push($players_moving_on, $i);
  }
}

echo var_dump($players_moving_on);
header("Location: assignment_display.php");

$CURRENT_ROUND++;


$attacker_array = $players_moving_on;

// Make a copy of the attacker array available for assignment
$target_pool = (new ArrayObject($attacker_array))->getArrayCopy();

// Assign Targets
$target_array = array();
foreach ($attacker_array as $attacker)
{
  // Select a random target from the pool
  $random_index = rand(0, count($target_pool) - 1);
  $potential_target = $target_pool[$random_index];

  //  Remove the target from the pool so it can't be reused
  array_splice($target_pool, $random_index, 1);

  // Safety check: if the $potential_target is the same as the attacker in which case we need to do some more work
  if ($potential_target == $attacker)
  {
      if (count($target_pool) > 1) // If there are any other available targets, then pull another from the pool
      {
          $random_index = rand(0, count($target_pool) - 1);
          $second_target = $target_pool[$random_index];

          //  Remove the target from the pool so it can't be reused
          array_splice($target_pool, $random_index, 1);

          // Put the original target back in the pool
          array_push($target_pool, $potential_target);

          $potential_target = $second_target;
      }
      else // We're on the last attacker and have to swap this potential target with something already in the target_array
      {
          $temp = $target_array[0];
          $target_array[0] = $potential_target;
          $potential_target = $temp;
      }
  }
  // Add the target to the final array
  array_push($target_array, $potential_target);
}

// Now insert the records into the database
for ($c = 0; $c < count($target_array); $c++) {
  $sql = <<<SQL
      INSERT INTO assignments (attacker_id, target_id, assignment_status, assignment_round)
      VALUES ($attacker_array[$c], $target_array[$c], 0, $CURRENT_ROUND)
SQL;

  $conn->query($sql);
}

$change_player_to_playing = "UPDATE players SET player_status = 0 WHERE assignment_id = ";
foreach ($players_moving_on as $player) {
  $conn->query($change_player_to_player . $player);
}


$conn->close();




// ONE QUERY FOR ARRAY OF ALL STATUSES TO MAKE IT FASTER
