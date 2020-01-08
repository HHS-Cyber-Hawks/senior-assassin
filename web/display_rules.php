<?php
include("environment.php");

// Create connection
$conn = create_connection();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM rules";

$result = $conn->query($sql);

echo "<table>"; // start a table tag in the HTML

while($row = $result->fetch_assoc()){   //Creates a loop to loop through results
  echo "" . $row["rule_id"]. " - " . $row["rule"]. "<br>";  //$row['index'] the index here is a field name
}

echo "</table>"; //Close the table in HTML
