<?php

include("environment.php");

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
            <span class="text">Name:</span><input type="text" name="name" style="width: 200px;"/>
            <br />
            <span class="text">Email:</span><input type="text" name="email" style="width: 200px;"/>
            <br />
            <br />
            <button class ='button' type="submit" name="submit">Save</button>
        </form>

        <button onclick="location.href = 'index.php?<?php echo $round; ?>'">Cancel</button>
    </body>
</html>

<?php

}
else
{

    // Sanitize all input values to prevent SQL injection exploits
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);

    // Prepare the query string (we use HEREDOC syntax to avoid messing around with double quotes and string concatentation)
    $sql = <<<SQL
    INSERT INTO players (player_name, email)
      VALUES ('$name', '$email')
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
