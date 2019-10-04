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

$sql = <<<SQL
          SELECT player_id
          FROM num_players
          ORDER BY player_id
          ;
SQL;


$get_count = <<<SQL
                SELECT count(player_id) as num_players
                FROM players
                ;
SQL;

$result = $conn->query($get_count);
$row = $result->fetch_assoc();

$num_players = $row["num_players"];

for($i = 1; $i <= $num_players; $i++) {
    $make_assignment = "INSERT INTO assignments(attacker_id, victim_id, the_status) " .
                       "VALUES(" . ($i + 1) . ", " . ($num_players - $i) . ", 0);";
    $conn->query($make_assignment);
    echo "Created <br/>";
}




$conn->close();

?>
