<html>
  <head>
    <title>Assignments</title>
    <script src='scripts.js?<?php echo rand(); ?>'></script>
    <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
  </head>
  <body>
    <div class="header">
      <span>
        <a href="assignment_create.php"><button class="button">Create Assignments</button></a>
        <a href="assignment_clear.php"><button class="button">Clear Assignments</button></a>
        <a href="new_round.php"><button class="button">Start Next Round</button></a>
        <a href="index.php"><button class="button">Back to Player List</button></a>
      </span>
      <br />
      <br />
      <span>
        <a href="clear_players.php"><button class="button">Clear Players</button></a>
        <a href=""><button class="button"></button></a>
        <a href=""><button class="button"></button></a>
        <a href=""><button class="button"></button></a>
        <a href=""><button class="button"></button></a>
      </span>
    </div>
    <br />
    <br />

<?php

include("environment.php");

// Create connection
$conn = create_connection();

// Check connection
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

$sql = <<<SQL
          SELECT assignment_id,
          attackers.first_name as attacker_first_name,
          attackers.last_name as attacker_last_name,
          targets.first_name as target_first_name,
          targets.last_name as target_last_name,
          assignment_status
          FROM assignments
          JOIN players attackers ON attackers.player_id = attacker_id
          JOIN players targets ON targets.player_id = target_id
          ;
SQL;

$result = $conn->query($sql);

if ($result->num_rows > 0)
{


    echo "<table id='resultsTable'>";
    echo "<tr><th>Assignment ID</th><th>Attacker</th><th>Target</th><th>Status</th><th>Change Status</th></tr>";

    while ($row = $result->fetch_assoc())
    {
        echo "<tr>";
        echo "<td>" . $row["assignment_id"]      . "</td>";
        echo "<td>" . $row["attacker_first_name"] . " " . $row["attacker_last_name"] . "</td>";
        echo "<td>" . $row["target_first_name"]   . " " . $row["target_last_name"]   . "</td>";
        echo "<td>";

        if ($row["assignment_status"] == 0)
        {
          echo "Open";
        }
        else if ($row["assignment_status"] == 1)
        {
          echo "Disputed";
        }
        else if ($row["assignment_status"] == 2)
        {
          echo "Confirmed";
        }
        else if ($row["assignment_status"] == 3)
        {
          echo "Obsolete";
        }

        echo "</td>";
        echo "<td>
              <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 0" . ")'>Open</button>
              <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 1" . ")'>Disputed</button>
              <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 2" . ")'>Confirmed</button>
              <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 3" . ")'>Obsolete</button>
              </td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br />";
}
else
{
    echo "<p style='text-align: center'>No Assignments</p>";
}

$conn->close();

?>

  </body>
</html>
