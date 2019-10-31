<?php

extract($_REQUEST);

if (!isset($submit)) {

?>

<html>
    <head>
        <title>Add Player</title>
        <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
    </head>

    <body>
        <h1> Add player </h1>
        <form action="player_add.php" method="post" style="margin-bottom: 5px;">
            <span class="label">First Name:</span><input type="text" name="firstName" style="width: 200px;"/>
            <br />
            <span class="label">Last Name:</span><input type="text" name="lastName" style="width: 200px;"/>
            <br />
            <span class="label">Email:</span><input type="text" name="email" style="width: 200px;"/>
            <br />
            <br />
            <button type="submit" name="submit">Save</button>
        </form>

        <button onclick="location.href = 'index.php'">Cancel</button>
    </body>
</html>

<?php

}
else
{

    include("environment.php");

    // Connect to the database and make sure it was successful
    $conn = create_connection();
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

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
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

?>
