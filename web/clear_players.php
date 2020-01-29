<?php

include("environment.php");

$round = $conn->real_escape_string($round);

$conn->query("DELETE FROM players");

$conn->close();

header("Location: index.php?round=$round");
