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
            <a href=".php"><button class="button">-----</button></a>
        </span>
    </div>

    <div>
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
          SELECT player_id, first_name, last_name, email
          FROM players
          ORDER BY last_name, first_name;
SQL;

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table id='resultsTable' >";
    echo "<tr><th>ID</th><th>Last Name</th><th>First Name</th><th>Email</th><th>Edit/Delete</th></tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["player_id"] . "</td>";
        echo "<td>" . $row["first_name"] . "</td>";
        echo "<td>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td><button onclick='deletePlayer(" . $row["player_id"] . ")'>Delete</button> <button onclick='editPlayer(" . $row["player_id"] . ")'>Edit</button></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br />";
} else {
    echo "0 results";
}
$conn->close();
?>
      <div style="width: auto; text-align: center; margin: auto;">
        <span> <a href="player_add.php"><button class="button">Add Player</button></a> </span>
        <span> <a href="import.php"><button class="button">Import Players</button></a> </span>
      </div>
    </div>
  </body>
</html>
