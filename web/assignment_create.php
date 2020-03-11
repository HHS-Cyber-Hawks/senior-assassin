<?php

include("environment.php");

// Queries database for the player ids and load them into the attacker array
$get_count = <<<SQL
                SELECT player_id
                FROM players
                ORDER BY player_id
                ;
SQL;

// Gets results from query
$result = $conn->query($get_count);

$attacker_array = array();
while ($row = $result->fetch_assoc())
{
  array_push($attacker_array, $row['player_id']);
}

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

  $sql = "SELECT email from players where player_id = $attacker";
  $player = get_value($sql, "email");

  $sql = "SELECT player_name from players where player_id = $potential_target";
  $name = get_value($sql, "player_name");

  send_email($player, "Your Assignment", "Your Target is: " . $name);
}

// Now insert the records into the database
for ($c = 0; $c < count($target_array); $c++) {
  $sql = <<<SQL
      INSERT INTO assignments (attacker_id, target_id, assignment_status, assignment_round)
      VALUES ($attacker_array[$c], $target_array[$c], 0, 1)
SQL;

  $conn->query($sql);
}

$conn->close();

header('Location: assignment_display.php?round=1');
