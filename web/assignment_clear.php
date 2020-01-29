<?php

include("environment.php");

$conn->query("TRUNCATE assignments");

$conn->close();

header('Location: assignment_display.php?round=1');
