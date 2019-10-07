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

$get_count = <<<SQL
                SELECT count(player_id) as num_players
                FROM players
                ;
SQL;

$result = $conn->query($get_count);
$row = $result->fetch_assoc();

$num_players = $row["num_players"];

$conn->close();

?>
