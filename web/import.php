
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

$row = 1;
if (($handle = fopen("class-of-2020-small.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // count finds how many values per row
        $num = count($data);
        $row++;

        $firstName = $conn->real_escape_string($data[0]);
        $lastName = $conn->real_escape_string($data[1]);
        $email = $conn->real_escape_string($data[2]);

        $sql = <<<SQL
            INSERT INTO players (first_name, last_name, email)
              VALUES ('$firstName', '$lastName', '$email')
SQL;

        $conn->query($sql);
    }

    fclose($handle);

    echo "Imported " . $row . " records";
}

header('Location: index.php');
