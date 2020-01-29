<?php

include("environment.php");

$round = $conn->real_escape_string($round);

if (!isset($submit)) {

?>

<html>
    <head>
        <title>Edit Player</title>
        <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
    </head>

    <body>
        <h1>Edit Player</h1>

<?php

// Connect to the database and make sure it was successful
$conn = create_connection();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql1 = <<<SQL
          SELECT player_id, first_name, last_name, email
          FROM players
          WHERE player_id = $id;
SQL;

$result = $conn->query($sql1);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<form action='player_edit.php' method='post'>" .
             "<span class='label'>First Name:</span><input type='text' name='firstNameNew' value='" . $row["first_name"] . "' style='width: 200px;'/><br />" .
             "<span class='label'>Last Name</span><input type='text' name='lastNameNew' value='" . $row["last_name"] . "'  style='width: 200px;' /><br />" .
             "<span class='label'>Email</span><input type='text' name='emailNew' value='" . $row["email"] . "'  style='width: 200px;' /><br />" .
             "<input type='hidden' name='id' value='" . $row["player_id"] . "'/><br />" .
             "<button type='submit' name='submit'>Save</button>" .
             "</form>";
    }
}

?>

          <button onclick="location.href='index.php'">Cancel</button>
    </body>
</html>

<?php

}
else
{

// Connect to the database and make sure it was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize all input values to prevent SQL injection exploits
$firstNameNew = $conn->real_escape_string($firstNameNew);
$lastNameNew = $conn->real_escape_string($lastNameNew);
$emailNew = $conn->real_escape_string($emailNew);

$sql2 = <<<SQL
          UPDATE players
          SET first_name = "$firstNameNew",
          last_name = "$lastNameNew",
          email = "$emailNew"
          WHERE player_id = $id;
SQL;

// Execute the query and display the results
if ($conn->query($sql2) === TRUE) {
    //echo "Company edited successfully";
    header('Location: index.php?round=$round');
} else {
    echo "Error: " . $sql2 . "<br>" . $conn->error;
}

$conn->close();
}

?>
