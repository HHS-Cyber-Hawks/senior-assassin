<?php

extract($_REQUEST);
include("environment.php");

$conn = create_connection();

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

$round = $conn->real_escape_string($round);



// Gets the player status given the player id
$get_player_status = "SELECT player_status FROM players WHERE player_id = ";

// Gets the assignment id given the target id
$get_id_when_target = "SELECT assignment_id FROM assignments WHERE assignment_round = $round AND target_id = ";

// Sets the assignment_status to "3" (obsolete) given the assignement id
$change_to_obsolete = "UPDATE assignments SET assignment_status = 3 WHERE assignment_id = ";

// Sets the player status to "1" (moving on) given the player id
$moving_on = "UPDATE players SET player_status = 1 WHERE player_id = ";

// Sets the player status to "-1" (out) given the player id
$did_not_eliminate = "UPDATE players SET player_status = -1 WHERE player_id = ";

// Gets the round of the given assignment id
$get_round = "SELECT assignment_round FROM assignments WHERE assignment_id = ";

// Makes an array of the assignment ids
$sql = "SELECT * FROM assignments";
$result = $conn->query($sql);

while($row = $result->fetch_assoc())
{
  $assignment_id = $row["assignment_id"];
  // Gets the status of the assignment
  $status = $row["assignment_status"];
  // Gets the attacker id of the assignment
  $attacker_id = $row["attacker_id"];
  // Gets the round on the assignment
  $assignment_round = $row["assignment_round"];

  if ($status == 2 && $assignment_round == $round)
  {
      $sql = "SELECT player_status FROM players WHERE player_id = " . $row['attacker_id'];
      $player_status = get_value($sql, "player_status");
      echo "PLAYER STATUS INSIDE IF: " . $player_status . "<br />";
      //This if block prevents someone who has been eliminated to moving on to the next round
      if($player_status == 1)
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







// TODO Occasionally when there are only 2 people left it screws up making both assignments









// returns the number of players
$sql = "SELECT count(player_id) FROM players";
$num_players = get_value($sql, "count(player_id)");

// initializes players moving on array which will be a pool of players in the next round
$players_moving_on = array();

$sql = "SELECT * FROM players";
$result = $conn->query($sql);

$make_playing = "UPDATE players SET player_status = 0 WHERE player_id = ";

// Make an array of all of the player ids
while($row = $result->fetch_assoc())
{
  $player_id = $row["player_id"];
  $fname = get_value("SELECT first_name FROM players WHERE player_id = $player_id", "first_name");
  $lname = get_value("SELECT last_name FROM players WHERE player_id = $player_id", "last_name");

  // Gets the value to see if the player is actually going to move on
  $current_player_status = $row["player_status"];
  echo "PLAYER NAME: $fname " . $lname . "<br />";
  echo "PLAYER STATUS: " . $current_player_status . "<br /><br />";

  // If the player can move on then they get added to the player array for the next round
  if($current_player_status == 1)
  {
    array_push($players_moving_on, $player_id);
    $conn->query($make_playing . $player_id);
  }
}

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
