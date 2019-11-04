<?php


include("environment.php");

// Create connection
$conn = create_connection();

extract($_REQUEST);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
  $sql = "SELECT player_status FROM players WHERE player_id =" . $i;
  $current_player_status = get_value($sql);

  if($current_player_status == 1)
  {
    array_push($players_moving_on, $i);
  }
}

echo var_dump($players_moving_on);

$CURRENT_ROUND++;
