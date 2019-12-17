
<?php

include("environment.php");
$conn = create_connection();
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
          assignment_round
          FROM assignments
          JOIN players attackers ON attackers.player_id = attacker_id
          JOIN players targets ON targets.player_id = target_id
<<<<<<< HEAD
          WHERE (attacker_id = $id OR target_id = $id) AND assignment_status = 2
=======
          WHERE (attacker_id = $id OR target_id = $id) AND (assignment_status = 2)
>>>>>>> b9e1b6056d97a00bff88e1d8b2dbb28bfe39447e
          ORDER BY assignment_round;
SQL;
$result = $conn->query($sql);

$fname = "SELECT first_name FROM players WHERE player_id = $id";
$lname = "SELECT last_name FROM players WHERE player_id = $id";
$name = get_value($fname, "first_name") . " " . get_value($lname, "last_name");

echo "<html>";
echo "<head>" .
      "<script src='scripts.js?" . rand() . "'></script>" .
      "<link rel='stylesheet' type='text/css' href='styles.css?'" . rand() . "' />" .
      "</head>";

echo "<body>";
echo "<h1 style='text-align: center;'>" . $name . "</h1>";
echo "<a href='index.php?round=$round' style='text-align: center;'><p>back</p></a>";
if ($result->num_rows > 0) {
    echo "<table id='resultsTable' style='td{ width: 100px; }'>";
    echo "<tr><th>Attacker</th><th>Target</th><th>Status</th><th>Round</th></tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {
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

        echo "<td>" . $row["assignment_round"] . "</td>";

        echo "</tr>";
    }

    echo "</table>";
    echo "<br />";
}
else
{
  echo "<p style='text-align: center;'>No Player History</p>";
}
echo "</body>";
