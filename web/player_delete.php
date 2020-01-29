<?php

include("environment.php");

$round = $conn->real_escape_string($round);

// sql to delete a record
$sql = "DELETE FROM players WHERE player_id=" . $id;

if ($conn->query($sql) === TRUE) {
    header("Location: index.php?round=$round");
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
