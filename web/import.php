<?php
include("environment.php");

// Create connection
$conn = create_connection();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$row = 1;
if (($handle = fopen("class-of-2020.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // count finds how many values per row
        $num = count($data);
        $row++;

        $firstName = $conn->real_escape_string($data[0]);
        $lastName = $conn->real_escape_string($data[1]);
        $email = $conn->real_escape_string($data[2]);

        $sql = <<<SQL
            INSERT INTO players (first_name, last_name, email, player_status)
              VALUES ('$firstName', '$lastName', '$email', 0)
SQL;

        $conn->query($sql);
    }

    fclose($handle);

}
<<<<<<< HEAD

header("Location: index.php");
?>
=======
>>>>>>> ebea430f88aaa00a7cc2ad76f415a0d8d3926ff4
