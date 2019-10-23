<html>
  <head>
    <title>Assignments</title>
    <script src='scripts.js?<?php echo rand(); ?>'></script>
    <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
  </head>
  <body>
    <div class="header">
      <a href="assignment_create.php"><button class="button" style="height: auto; margins: auto;">Create Assignments</button></a>
      <a href="assignment_clear.php"><button class="button" style="height: auto; margins: auto;">Clear Assignments</button></a>
      <a href="index.php"><button class="button" style="height: auto; margins: auto;">Back to Player List</button></a>
    </div>

<?php

include("environment.php");

// Create connection
$conn = create_connection();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = <<<SQL
          SELECT assignment_id,
          attackers.first_name as attacker_first_name,
          attackers.last_name as attacker_last_name,
          targets.first_name as target_first_name,
          targets.last_name as target_last_name,
          status
          FROM assignments
          JOIN players attackers ON attackers.player_id = attacker_id
          JOIN players targets ON targets.player_id = target_id
          ;
SQL;

$result = $conn->query($sql);

if ($result->num_rows > 0) {


    echo "<table id='resultsTable'>";
    echo "<tr><th>Assignment ID</th><th>Attacker</th><th>Target</th><th>Status</th></tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["assignment_id"]      . "</td>";
        echo "<td>" . $row["attacker_first_name"] . " " . $row["attacker_last_name"] . "</td>";
        echo "<td>" . $row["target_first_name"]   . " " . $row["target_last_name"]   . "</td>";
        echo "<td>" . $row["status"]          . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br />";
} else {
    echo "0 results";
}

$conn->close();

?>

  </body>
</html>
