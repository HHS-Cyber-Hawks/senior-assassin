<?php

include("environment.php");
$conn = create_connection();

$sql = "UPDATE players SET player_status = 0";
$conn->query($sql);

header("Location: index.php");
