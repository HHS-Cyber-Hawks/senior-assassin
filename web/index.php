<?php
include("environment.php");

extract($_REQUEST);
if(!isset($round))
{
  $round = 1;
}
// Create connection
$conn = create_connection();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$round = $conn->real_escape_string($round);
?>

<html>
  <head>
    <title>Senior Assassin</title>
    <script src='scripts.js?<?php echo rand(); ?>'></script>
    <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
  </head>
  <body>
    <div class="header">
        <h1 class="title">Hanover Senior Assassin</h1>
    </div>
    <div class="button-header">
      <div>
        <span>
            <button class="current-button">Player List</button>
            <a href="assignment_display.php?round=<?php echo $round; ?>"><button class="button">Assignments</button></a>
        </span>
      </div>
      <div>
        <span>
            <a href="clear_players.php?round=<?php echo $round; ?>"><button class="lower-button">Clear Players</button></a>
            <a href="reset_players.php?round=<?php echo $round; ?>"><button class="lower-button">Reset Players</button></a>
        </span>
      </div>
      <div>
        <span>
          <a href="player_add.php?round=<?php echo $round; ?>"><button class="button">Add Player</button></a>
          <?php
            $sql = "SELECT count(player_id) FROM players";
            $num_records = get_value($sql, "count(player_id)");
            if ($num_records > 1)
            {
              echo "<button class='button'>Import File</button>";
            }
            else
            {
              echo "<a href='import_players.php?round=$round'><button class='button'>Import File</button></a>";
            }
          ?>
        </span>
      </div>
    </div>
    <br />
    <br />

<?php

$sql = <<<SQL
          SELECT player_id, first_name, last_name, email, player_status
          FROM players
          ORDER BY player_status DESC, last_name, first_name;
SQL;

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table id='resultsTable' >";
    echo "<tr><th>Last Name</th><th>First Name</th><th>Email</th><th>Status</th><th>Edit/Delete</th></tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["first_name"] . "</td>";
        echo "<td>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>";

        if ($row["player_status"] == -1)
        {
          echo "Out";
        }
        else if ($row["player_status"] == 0)
        {
          echo "Playing";
        }
        else if ($row["player_status"] == 1)
        {
          echo "Can move on";
        }
        else if ($row["player_status"] == 2)
        {
          echo "Moving on";
        }

        echo "</td>";
        echo "<td><button onclick='deletePlayer(" . $row["player_id"] . ")'>Delete</button> <button onclick='editPlayer(" . $row["player_id"] . ", $round)'>Edit</button></td></div>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br />";
} else {
    echo "<p style='text-align: center;'>No Players</p>";
}
$conn->close();
?>

    </div>
  </body>
</html>
