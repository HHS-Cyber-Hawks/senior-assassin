
<?php
//copy of player history, but only showing attacker's target currently open
include("environment.php");
$conn = create_connection();
$id =  $_SESSION['userId'];// $conn->real_escape_string($id);

$round = $conn->real_escape_string($round);

// echo $id;
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
          WHERE attacker_id = $id AND assignment_status = 0
          ORDER BY assignment_round;
SQL;
$result = $conn->query($sql);

$name = get_value();
echo $name;
echo "<html>";
echo "<head>" .
      "<script src='scripts.js?" . rand() . "'></script>" .
      "<link rel='stylesheet' type='text/css' href='styles.css?'" . rand() . "' />" .
      "</head>";

echo "<body>";
echo "<h1 style='text-align: center;'>" . $name . "</h1>";
echo "<div class='button-header'>
        <div>
          <span>
            <a href='index.php?round= <?php echo $round; ?>'><button class='lower-button'>Back</button></a>
          </span>
        </div>
      </div>
      <br />
      <br />";
if ($result->num_rows > 0) {
    echo "<table id='resultsTable' style='td{ width: 100px; }'>";
    echo "<tr><th>Attacker</th><th>Target</th><th>Round</th></tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["attacker_first_name"] . " " . $row["attacker_last_name"] . "</td>";
        echo "<td>" . $row["target_first_name"]   . " " . $row["target_last_name"]   . "</td>";
        echo "<td>" . $row["assignment_round"] . "</td>";

        echo "</tr>";
    }

    echo "</table>";
    echo "<br />";
}
else
{
  echo "<p style='text-align: center;'>No Target</p>";
}
echo "</body>";
