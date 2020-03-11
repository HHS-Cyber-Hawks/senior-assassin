<?php

include("environment.php");

$round = $conn->real_escape_string($round);

?>

<html>
  <head>
    <title>Assignments</title>
    <script src='scripts.js? rand(); '></script>
    <link rel='stylesheet' type='text/css' href='styles.css?<?php echo rand(); ?>' />
  </head>
  <body>
    <div class="dropdown">
      <button class="dropbtn"><?php echo $_SESSION['displayName'] ?></button>
      <div class="dropdown-content">
        <p><?php echo $_SESSION['email'] ?></p>
        <a href="logout.php">Log out</a>
      </div>
    </div>
    <div class='header'>
      <h1 class='title'>Hanover Senior Assassin</h1>
    </div>
    <div class='button-header'>
      <div>
        <a href='index.php?round=<?php echo $round; ?>'><button class='button'>Player List</button></a>
        <a href='see_target?round=<?php echo $round; ?>'><button class='button'>My Target</button></a>
        <a href=''><button class="current-button">Feed</button></a>
      </div>
    </div>
    <br />
    <br />

<?php

$sql = <<<SQL
          SELECT assignment_id,
          attackers.player_name as attacker_name,
          targets.player_name as target_name,
          assignment_status,
          assignment_round,
          date_format(assignment_timestamp, '%M %d %r') as formatted_date
          FROM assignments
          JOIN players attackers ON attackers.player_id = attacker_id
          JOIN players targets ON targets.player_id = target_id
          WHERE assignment_status = 2
          ORDER BY formatted_date;
SQL;

$result = $conn->query($sql);

if ($result->num_rows > 0)
{
    echo "<table id='resultsTable'>";
    echo "<tr> <th>Attacker</th> <th>Target</th> <th>Status</th> <th>Time</th> </tr>";

    while ($row = $result->fetch_assoc())
    {
        echo "<tr>";
        echo "<td>" . $row["attacker_first_name"] . " " . $row["attacker_last_name"] . "</td>";
        echo "<td>" . $row["target_first_name"]   . " " . $row["target_last_name"]   . "</td>";
        echo "<td>" . $row["formatted_date"] . "</td>";
        echo "<td style='background-color: green>Confirmed</td>";
    }

    echo "</table>";
    echo "<br />";
}

$conn->close();



?>

  </body>
</html>
