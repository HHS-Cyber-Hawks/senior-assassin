<?php

include("environment.php");

$round = $conn->real_escape_string($round);

?>

<html>
  <head>
    <title>Assignments</title>
    <script src='scripts.js? rand(); '></script>
    <link rel='stylesheet' type='text/css' href='styles.css?<?php echo rand(); ?>' />
  </head>
  <body>
    <div class="dropdown">
      <button class="dropbtn"><?php echo $_SESSION['displayName'] ?></button>
      <div class="dropdown-content">
        <p><?php echo $_SESSION['email'] ?></p>
        <a href="logout.php">Log out</a>
      </div>
    </div>
    <div class='header'>
      <h1 class='title'>Hanover Senior Assassin</h1>
    </div>
    <div class='button-header'>
      <div>
        <a href='index.php?round=<?php echo $round; ?>'><button class='button'>Player List</button></a>
        <a href=''><button class='current-button'>Assignments</button></a>
      </div>
      <div>
        <a href='assignment_create.php'><button class='lower-button'>Create Assignments</button></a>
        <button onclick = "confirmClear()" class='lower-button'>Clear Assignments</button>
        <script>
          function confirmClear() {
            var txt;
            if (confirm("Do you want to clear the Asssignments?")) {
              window.location = "assignment_clear.php";
            }
          }
        </script>
        <a href='start_next_round.php?round=<?php echo $round; ?>'><button class='lower-button'>Start Next Round</button></a>
      </div>
      <div>
        <?php

          $get_max_round = "SELECT max(assignment_round) FROM assignments";
          $max_round = intval(get_value($get_max_round, "max(assignment_round)"));

          $sql = "SELECT count(*) FROM players WHERE player_status = 0";
          $num_players_left = get_value($sql, "count(*)");

          if ($max_round != 1)
          {
            echo "<br />";
            for ($i = 1; $i <= $max_round; $i++)
            {
              echo "<a href='assignment_display?round=$i'><button>Round $i</button></a> &nbsp &nbsp";
            }

            if ($num_players_left == 1)
            {
              echo "<a href='assignment_display?round=" . ($max_round + 1) . "'><button>Winner</button></a>";
            }
          }
        ?>
      </div>
    </div>
    <br />
    <br />

<?php
  echo "<hr>";
  if ($round != $max_round + 1)
  {
    echo "<h1 class='title' style='text-align: center;'>ROUND $round</h1>";
  }
  else if ($round == $max_round + 1 && $num_players_left == 1)
  {
    echo "<h1 class='title' style='text-align: center;'>WINNER</h1>";
  }

$sql = <<<SQL
          SELECT assignment_id,
          attackers.player_name as attacker_name,
          targets.player_name as target_name,
          assignment_status,
          assignment_round
          FROM assignments
          JOIN players attackers ON attackers.player_id = attacker_id
          JOIN players targets ON targets.player_id = target_id
          WHERE assignment_round = $round
          ;
SQL;

$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0)
    {

      echo "<table id='resultsTable'>";
      echo "<tr> <th>Attacker</th> <th>Target</th> <th>Status</th> <th>Change Status</th> </tr>";

      while ($row = $result->fetch_assoc())
      {
          echo "<tr>";
          echo "<td>" . $row["attacker_name"] .  "</td>";
          echo "<td>" . $row["target_name"]   .  "</td>";
          echo "<td style='background-color: ";

          if ($row["assignment_status"] == 0)
          {
            echo "'>Open";
          }
          else if ($row["assignment_status"] == 1)
          {
            echo "'>Pending";
          }
          else if ($row["assignment_status"] == 2)
          {
            echo "green'>Confirmed";
          }
          else if ($row["assignment_status"] == 3)
          {
            echo "black'>Obsolete";
          }

          echo "</td>";

          echo "<td style='width: 400px'>
                <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 0" . ", " . $round . ")'>Open</button>
                <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 1" . ", " . $round . ")'>Pending</button>
                <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 2" . ", " . $round . ")'>Confirmed</button>
                <button style='width: 80px' onclick='updateStatus(" . $row["assignment_id"] . ", 3" . ", " . $round . ")'>Obsolete</button>
                </td>";
          echo "</tr>";
      }

      echo "</table>";
      echo "<br />";
    }
    else if ($num_players_left == 1)
    {
      $sql = "SELECT first_name, last_name FROM players WHERE player_status = 0";
      $result = $conn->query($sql);
      while($row = $result->fetch_assoc())
      {
        echo "<p style='text-align: center; font-family: arial; font-size: 20px;'>" . $row['first_name'] . " " . $row['last_name'] . "</p>";
      }
    }
}

$conn->close();



?>

  </body>
</html>
