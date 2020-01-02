<?php

include("environment.php");

// Create connection
$conn = create_connection();

$round = $conn->real_escape_string($round);

?>

<html>
  <head>
    <title>Assignments</title>
    <script src='scripts.js? rand(); '></script>
    <link rel='stylesheet' type='text/css' href='styles.css?<?php echo rand(); ?>' />
  </head>
  <body>
    <div class='header'>
      <h1 class='title'>Hanover Senior Assassin</h1>
    </div>
    <div class='button-header'>
      <div>
        <a href='index.php?round=<?php echo $round; ?>'><button class='button'>Player List</button></a>
        <a href=''><button class='current-button'>Assignments</button></a>
      </div>
      <div>
        <a href='assignment_create.php'><button class='lower-button'>Create Assignments</button></a>
        <a href='assignment_clear.php'><button class='lower-button'>Clear Assignments</button></a>
        <a href='start_next_round.php?round=<?php echo $round; ?>'><button class='lower-button'>Start Next Round</button></a>
      </div>
    </div>
    <br />
    <br />

<?php

echo "<h1 class='title' style='text-align: center;'>ROUND $round</h1>";

$sql = <<<SQL
          SELECT assignment_id,
          attackers.first_name as attacker_first_name,
          attackers.last_name as attacker_last_name,
          targets.first_name as target_first_name,
          targets.last_name as target_last_name,
          assignment_status,
          assignment_round
          FROM assignments
          JOIN players attackers ON attackers.player_id = attacker_id
          JOIN players targets ON targets.player_id = target_id
          WHERE assignment_round = $round
          ;
SQL;

$result = $conn->query($sql);

if ($result->num_rows > 0)
{

  echo "<table id='resultsTable'>";
  echo "<tr> <th>Attacker</th> <th>Target</th> <th>Status</th> <th>Change Status</th> </tr>";

  while ($row = $result->fetch_assoc())
  {
      echo "<tr>";
      echo "<td>" . $row["attacker_first_name"] . " " . $row["attacker_last_name"] . "</td>";
      echo "<td>" . $row["target_first_name"]   . " " . $row["target_last_name"]   . "</td>";
      echo "<td style='background-color: ";

      if ($row["assignment_status"] == 0)
      {
        echo "'>Open";
      }
      else if ($row["assignment_status"] == 1)
      {
        echo "'>Pending";
      }
      else if ($row["assignment_status"] == 2)
      {
        echo "green'>Confirmed";
      }
      else if ($row["assignment_status"] == 3)
      {
        echo "black'>Obsolete";
      }

      echo "</td>";

      echo "<td style='width: 400px'>
            <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 0" . ", " . $round . ")'>Open</button>
            <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 1" . ", " . $round . ")'>Pending</button>
            <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 2" . ", " . $round . ")'>Confirmed</button>
            <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 3" . ", " . $round . ")'>Obsolete</button>
            </td>";
      echo "</tr>";
  }

  echo "</table>";
  echo "<br />";
}

$conn->close();



?>

  </body>
</html>
