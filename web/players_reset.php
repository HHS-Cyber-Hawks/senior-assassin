<?php

include("environment.php");

$round = $conn->real_escape_string($round);

$sql = "UPDATE players SET player_status = 0";
$conn->query($sql);

header("Location: index.php?round=$round");

?>
