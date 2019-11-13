<?php

extract($_REQUEST);
include("environment.php");

$conn = create_connection();

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

$round = $conn->real_escape_string($round);

// Gets the number of assignments
$sql = "SELECT count(assignment_id) FROM assignments";
$num_assignments = get_value($sql, "count(assignment_id)");

// Makes an array of the assignment ids
$sql = "SELECT assignment_id FROM assignments";
$result = $conn->query($sql);
$assignment_array = array();
while($row = $result->fetch_assoc())
{
  array_push($assignment_array, $row["assignment_id"]);
}

// Gets the status of the assignment given the assignment id
$get_assignment_status = "SELECT assignment_status FROM assignments WHERE assignment_id = ";

// Gets the player status given the player id
$get_player_status = "SELECT player_status FROM players WHERE player_id = ";

// Gets the assignment id given the target id
$get_id_when_target = "SELECT assignment_id FROM assignments WHERE assignment_round = $round AND target_id = ";

// Sets the assignment_status to "3" (obsolete) given the assignement id
$change_to_obsolete = "UPDATE assignments SET assignment_status = 3 WHERE assignment_id = ";

// Gets the attacker id given the assignment id
$get_attacker_id = "SELECT attacker_id FROM assignments WHERE assignment_id = ";

// Sets the player status to "1" (moving on) given the player id
$moving_on = "UPDATE players SET player_status = 1 WHERE player_id = ";

// Sets the player status to "-1" (out) given the player id
$did_not_eliminate = "UPDATE players SET player_status = -1 WHERE player_id = ";

// Gets the round of the given assignment id
$get_round = "SELECT assignment_round FROM assignments WHERE assignment_id = ";

for ($i = 0; $i < $num_assignments; $i++)
{
  $assignment_id = $assignment_array[$i];

  // Gets the status of the assignment
  $status = get_value($get_assignment_status . $assignment_id, "assignment_status");
  $attacker_id = get_value($get_attacker_id . $assignment_id, "attacker_id");
  // echo "ATTACKER ID: $attacker_id <br />";

  // echo "i: $i <br />";
  // echo "ATTACKER ID: $attacker_id <br />";
  // echo "ASSIGNMENT ID: $assignment_id <br />";

  // Gets the round on the assignment
  $assignment_round = get_value($get_round . $assignment_id, "assignment_round");

  if ($status == 2 && $assignment_round == $round)
  {
    // $as_target_id returns the assignment id of where the current index is the target
    $as_target_id = get_value($get_id_when_target . $attacker_id, "assignment_id");
    // $sql = "SELECT target_id FROM assignments WHERE assignment_id = $as_target_id";
    // $id1 = get_value($sql, "target_id");


    // $target_status returns the status of the attacker when he is a target
    $target_status = get_value($get_player_status . $as_target_id, "player_status");


    // echo "ASSIGNMENT ID: $assignment_id <br />";
    //
    // $sql = "SELECT first_name FROM players WHERE player_id = $id1";
    // $name = get_value($sql, "first_name");
    // echo "TARGET NAME: $name <br />";
    // echo "TARGET STATUS: $target_status <br />";
    // echo "<br />";

    //This if block prevents someone who has been eliminated to moving on to the next round
    if($target_status != 2)
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

  // echo "<br />";
}







// TODO Occasionally when there are only 2 people left it screws up making both assignments









// returns the number of players
$sql = "SELECT count(player_id) from players";
$num_players = get_value($sql, "count(player_id)");

// initializes players moving on array which will be a pool of players in the next round
$players_moving_on = array();

$sql = "SELECT player_id FROM players";
$result = $conn->query($sql);
$player_array = array();

// Make an array of all of the player ids
while($row = $result->fetch_assoc())
{
  array_push($player_array, $row["player_id"]);
}

echo "PLAYER ARRAY: " . var_dump($player_array) . "<br />";

// Makes the array of players that are moving on to the next round
$make_playing = "UPDATE players SET player_status = 0 WHERE player_id = ";
for($i = 0; $i < $num_players; $i++)
{
  $player = $player_array[$i];

  echo "i: $i <br />";

  // Gets the value to see if the player is actually going to move on
  $sql = "SELECT player_status FROM players WHERE player_id = $player";
  $current_player_status = get_value($sql, "player_status");
  echo $current_player_status;

  echo "PLAYER STATUS: $current_player_status <br />";

  // If the player can move on then they get added to the player array for the next round
  if($current_player_status == 1)
  {
    echo "$player MOVING ON <br />";
    array_push($players_moving_on, $player);
    $conn->query($make_playing . $player);
  }

  echo "<br />";
}

echo "PLAYERS MOVING ON: ";
echo var_dump($players_moving_on);

$round++;

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

// echo "TARGET ARRAY: ";
// echo var_dump($target_array);


echo "count(target_array): " . count($target_array) . " <br />";
// Now insert the records into the database
for ($c = 0; $c < count($target_array); $c++) {
  $sql = "INSERT INTO assignments (attacker_id, target_id, assignment_status, assignment_round) VALUES ($attacker_array[$c], $target_array[$c], 0, $round)";
  $conn->query($sql);
}

$change_player_to_playing = "UPDATE players SET player_status = 0 WHERE assignment_id = ";
foreach ($players_moving_on as $player) {
  $conn->query($change_player_to_playing . $player);
}

header("Location: assignment_display.php?round=" . $round);
