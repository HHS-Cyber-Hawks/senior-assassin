<?php

$servername = "mysql.server295.com";
$username = "assassin";
$password = "billiard gale seeing";
$dbname = "passingf_assassin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Queries database for the player ids and load them into the attacker array
$get_count = <<<SQL
                SELECT player_id
                FROM players
                ;
SQL;

// Gets results from query
$result = $conn->query($get_count);

$attacker_array = array();
while($row = $result->fetch_assoc())
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

// TODO: REMOVE "DIRECT" CIRCULAR DEPENDENCY (Joe attacks Bill, Bill attacks Joe)

// Now insert the records into the database
for ($c = 0; $c < count($target_array); $c++) {
  $sql = <<<SQL
      INSERT INTO assignments (attacker_id, target_id, status)
        VALUES ($attacker_array[$c], $target_array[$c], 0)
SQL;

  $conn->query($sql);
}

function clear() {
  sql = "TRUNCATE TABLE assignments;";

  $conn->query($sql);
}

$conn->close();

header('Location: assignment_display.php');
