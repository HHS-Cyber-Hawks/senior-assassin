<html>
  <head>
    <title>Senior Assassin</title>
    <script src='scripts.js?<?php echo rand(); ?>'></script>
    <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
  </head>
  <body>
    <div class="header">
        <h1 class="title">Hanover Senior Assassin</h1>
        <span>
            <button class="button" id="current">Home</button>
        </span>
        &nbsp;
        <span>
            <a href="assignment_display.php"><button class="button">Assignments</button></a>
        </span>
        &nbsp;
        <span>
            <a href="clear_players.php"><button class="button">Clear Players</button></a>
        </span>
    </div>

    <br />
    <br />

    <div>
<?php
include("environment.php");

// Create connection
$conn = create_connection();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = <<<SQL
          SELECT player_id, first_name, last_name, email, player_status
          FROM players
          ORDER BY last_name, first_name, player_id;
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
          echo "Moving on";
        }

        echo "</td>";
        echo "<td><button onclick='deletePlayer(" . $row["player_id"] . ")'>Delete</button> <button onclick='editPlayer(" . $row["player_id"] . ")'>Edit</button></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br />";
} else {
    echo "<p style='text-align: center;'>No Players</p>";
}
$conn->close();
?>
      <div style="width: auto; text-align: center; margin: auto;">
        <span> <a href="player_add.php"><button class="button">Add Player</button></a> </span>
        <span> <a href="uploadTest.php"><button class="button">Import Players</button></a> </span>
      </div>
    </div>
  </body>
</html>
