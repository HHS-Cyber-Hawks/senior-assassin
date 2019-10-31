<?php

extract($_REQUEST);

include("environment.php");

$conn = create_connection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$assignment_id = $id;

$sql = "UPDATE assignments SET assignment_status = $status WHERE assignment_id=" . $assignment_id;
$conn->query($sql);

$got_out = "UPDATE players SET player_status = -1 WHERE player_id = ";
$get_target_id = "SELECT target_id FROM assignments WHERE assignment_id = ";
$result = $conn->query($get_target_id . $assignment_id);
$row = $result->fetch_assoc();
$target_id = $row["target_id"];
$conn->query($got_out . $target_id);

$conn->close();

header("Location: assignment_display.php");
