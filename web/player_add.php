<?php

include("environment.php");

extract($_REQUEST);

include("environment.php");

// Connect to the database and make sure it was successful
$conn = create_connection();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$round = $conn->real_escape_string($round);

if (!isset($submit)) {

?>


<html>
    <head>
        <title>Add Player</title>
        <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
    </head>

    <body>
        <h1 class = 'title'> Add player </h1>
        <form action="player_add.php" method="post" style="margin-bottom: 5px;">
            <span class="text">First Name:</span><input type="text" name="firstName" style="width: 200px;"/>
            <br />
            <span class="text">Last Name:</span><input type="text" name="lastName" style="width: 200px;"/>
            <br />
            <span class="text">Email:</span><input type="text" name="email" style="width: 200px;"/>
            <br />
            <br />
            <button class ='button' type="submit" name="submit">Save</button>
        </form>

<<<<<<< HEAD
        <button class = 'lower-button' onclick="location.href = 'index.php'">Cancel</button>
=======
        <button onclick="location.href = 'index.php?<?php echo $round; ?>'">Cancel</button>
>>>>>>> 0e291f96326367acf0a004f23ef81273862087f9
    </body>
</html>

<?php

}
else
{

    // Sanitize all input values to prevent SQL injection exploits
    $firstName = $conn->real_escape_string($firstName);
    $lastName = $conn->real_escape_string($lastName);
    $email = $conn->real_escape_string($email);

    // Prepare the query string (we use HEREDOC syntax to avoid messing around with double quotes and string concatentation)
    $sql = <<<SQL
    INSERT INTO players (first_name, last_name, email)
      VALUES ('$firstName', '$lastName', '$email')
SQL;

    // Execute the query and display the results
    if ($conn->query($sql) === TRUE) {
        //echo "New company created successfully";
        header("Location: index.php?round=$round");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

?>
