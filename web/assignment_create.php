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

// Queries database for the amount of player ids there are
$get_count = <<<SQL
                SELECT count(player_id) as num_players
                FROM players
                ;
SQL;

$result = $conn->query($get_count);
$row = $result->fetch_assoc();

// Assigns num_players to the amount of players in the players table
$num_players = $row["num_players"];

// Makes the attacker array and fills it with the values 1 -> num_players
$attacker_array = array();
for($i = 1; $i <= $num_players; $i++)
{
    array_push($attacker_array, $i);
}

// Makes the victim array and sets it equal to the attacker array
$victim_array = (new ArrayObject($attacker_array))->getArrayCopy();
// Shuffles the victim array
shuffle($victim_array);
// Checks to make sure no attacker has themself as a target. If this does occur, the value is switched
checkArray($attacker_array, $victim_array, $num_players);

// Checks all indices of both matrices and makes sure they aren't the same. Swaps values in victim_array if they are
function checkArray($x, &$y, $num_players)
{
  for($i = 0; $i < $num_players; $i++)
  {
    if($x[$i] == $y[$i])
    {
      if($i == $num_players - 1)
      {
        $temp = $y[$i];
        $y[$i] = $y[$i - 1];
        $y[$i - 1] = $temp;
      }
      else
      {
        $temp = $y[$i];
        $y[$i] = $y[$i + 1];
        $y[$i + 1] = $temp;
      }
    }
  }
}

$conn->close();

?>
