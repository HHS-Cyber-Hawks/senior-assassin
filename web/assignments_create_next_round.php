<?php


include("environment.php");

// Create connection
$conn = create_connection();

extract($_REQUEST);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
