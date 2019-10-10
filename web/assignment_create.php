
<html>
  <body>


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

// Gets rid of the assignments table if it currently exists
$drop_table = <<<SQL
                 DROP TABLE IF EXISTS assignments;
SQL;

$conn->query($drop_table);

// Recreates the assignments table with no values inside of it
$make_table = <<<SQL
                  CREATE TABLE assignments (
                  assignment_id  INT NOT NULL AUTO_INCREMENT,
                  attacker_id    INT NOT NULL,
                  target_id      INT NOT NULL,
                  status         INT default 0,
                  PRIMARY KEY (assignment_id))
                  ;
SQL;

$conn->query($make_table);


// Queries database for the amount of player ids there are
$get_count = <<<SQL
                SELECT count(player_id) as num_players
                FROM players
                ;
SQL;

// Gets results from query
$result = $conn->query($get_count);
$row = $result->fetch_assoc();

// Assigns num_players to the amount of players in the players table
$num_players = $row["num_players"];

// Makes the attacker array and fills it with the values 1 -> num_players
$attacker_array = array();
for($i = 1; $i <= $num_players; $i++)
{
    array_push($attacker_array, $i);
}

$target_array = array();
$rand_num_array = array();

while(sizeof($target_array) < $num_players)
{
  // to push
  $rand_num = rand(0, $num_players - 1);

  if(!contains($rand_num_array, $rand_num))
  {
    array_push($target_array, $rand_num);
    array_push($rand_num_array, $rand_num);
  }
}

var_dump($target_array);





// CHECKS GO IN HERE
function contains(&$array, $to_check)
{
  for ($i=0; $i < sizeof($array); $i++) {
    if($array[$i] == $to_check)
    {
      return true;
    }
  }
  return false;
}











// Pushes the pairings into the assignments table
for($i = 0; $i < $num_players; $i++)
{
  $sql = <<<SQL
            INSERT INTO assignments (attacker_id, target_id, status)
            VALUES ('$attacker_array[$i]', '$target_array[$i]', 0);
SQL;

  $conn->query($sql);
}

$conn->close();

//header('Location: assignment_display.php');

?>



  </body>
</html>
