<?php

include("environment.php");
extract($_REQUEST);
// Create connection
$conn = create_connection();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$round = $conn->real_escape_string($round);

$conn->query("DELETE FROM players");

$conn->close();

header("Location: index.php?round=$round");
