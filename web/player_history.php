<?php

include("environment.php");

extract($_REQUEST);
$id = $conn->real_escape_string($id);
$round = $conn->real_escape_string($round);

$sql = <<<SQL
          SELECT assignment_id,
          attackers.first_name as attacker_first_name,
          attackers.last_name as attacker_last_name,
          targets.first_name as target_first_name,
          targets.last_name as target_last_name,
          assignment_status,
          assignment_round,
          date_format(assignment_timestamp, '%M %d %r') as formatted_date

          FROM assignments
          JOIN players attackers ON attackers.player_id = attacker_id
          JOIN players targets ON targets.player_id = target_id
          WHERE (attacker_id = $id OR target_id = $id) AND assignment_status = 2
          ORDER BY assignment_round;
SQL;
$result = $conn->query($sql);

$fname = "SELECT first_name FROM players WHERE player_id = $id";
$lname = "SELECT last_name FROM players WHERE player_id = $id";
$name = get_value($fname, "first_name") . " " . get_value($lname, "last_name");

?>

<html>
  <head>
      <script src='scripts.js? <?php echo rand(); ?> '></script>
      <link rel='stylesheet' type='text/css' href='styles.css? <?php echo rand() ?> '/>
  </head>

  <body>
    <h1 style='text-align: center; font-family: arial; color: #1772fc;'> <?php echo $name; ?> </h1>
    <div class="button-header">
      <div>
        <span>
          <a href='index.php?round= <?php echo $round; ?>'><button class="lower-button">Back</button></a>
        </span>
      </div>
    </div>
    <br />
    <br />

<?php
if ($result->num_rows > 0) {
    echo "<table id='resultsTable' style='td{ width: 100px; }'>";
    echo "<tr><th>Attacker</th><th>Target</th><th>Round</th><th>Timestamp</th></tr>";
    $timeStamp = substr("assignment_timestamp" , 3);
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["attacker_first_name"] . " " . $row["attacker_last_name"] . "</td>";
        echo "<td>" . $row["target_first_name"]   . " " . $row["target_last_name"]   . "</td>";
        echo "<td>" . $row["assignment_round"] . "</td>";
        echo "<td>" . $row["formatted_date"] . "</td>";

        echo "</tr>";
    }

    echo "</table>";
    echo "<br />";
}
else
{
  echo "<p class='text' style='text-align: center;'>No Player History</p>";
}
?>

</body>
