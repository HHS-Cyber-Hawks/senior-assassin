<html>
  <head>
    <title>Assignments</title>
    <script src='scripts.js?<?php echo rand(); ?>'></script>
    <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
  </head>

<?php

$servername = "mysql.server295.com";
$username = "assassin";
$password = "billiard gale seeing";
$dbname = "passingf_assassin";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = <<<SQL
          SELECT assignment_id,
          attackers.first_name as attacker_first_name,
          attackers.last_name as attacker_last_name,
          victims.first_name as victim_first_name,
          victims.last_name as victim_last_name,
          the_status
          FROM assignments
          JOIN players attackers ON attackers.player_id = attacker_id
          JOIN players victims ON victims.player_id = victim_id
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
        echo "<td>" . $row["victim_first_name"]   . " " . $row["victim_last_name"]   . "</td>";
        echo "<td>" . $row["the_status"]          . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br />";
} else {
    echo "0 results";
}

$conn->close();
?>

</html>
