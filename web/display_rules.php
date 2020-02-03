<?php
include("environment.php");

$sql = "SELECT * FROM rules";

$result = $conn->query($sql);

echo "<table>"; // start a table tag in the HTML

while($row = $result->fetch_assoc()){   //Creates a loop to loop through results
  echo "" . $row["rule_id"]. " - " . $row["rule"]. "<br>";  //$row['index'] the index here is a field name
}

echo "</table>"; //Close the table in HTML

echo "<a href='index.php?round=<?php echo $round; ?>'><button class='button'>Player List</button></a>";
