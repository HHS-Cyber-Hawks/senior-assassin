<?php

include("environment.php");

// Create connection
$conn = create_connection();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("DELETE FROM players");

$conn->close();

header('Location: index.php');
