<?php
include("environment.php");

if(!isset($round))
{
  $round = 1;
}

$round = $conn->real_escape_string($round);

// var_dump($_SESSION);

?>

<html>
  <head>
    <title>Senior Assassin</title>
    <script src='scripts.js?<?php echo rand(); ?>'></script>
    <link rel="stylesheet" type="text/css" href="styles.css?<?php echo rand(); ?>" />
  </head>
  <body>
    <div class="dropdown">
      <button class="dropbtn"><?php echo $_SESSION['displayName'] ?></button>
      <div class="dropdown-content">
        <p><?php echo $_SESSION['email'] ?></p>
        <a href="logout.php">Log out</a>
      </div>
    </div>

    <div class="header">
        <h1 class="title">Hanover Senior Assassin</h1>
    </div>
    <div class="button-header">
      <div>
        <span>
            <button class="current-button">Player List</button>
            <?php if(isAdmin()){ ?>
            <a href="assignment_display.php?round=<?php echo $round; ?>"><button class="button">Assignments</button></a>
            <a href="approve_players.php?round=<?php echo $round; ?>"><button class="button">Yet to Pay</button></a>
            <?php } else {  //if(isAdmin()) ?>
            <a href="see_target.php?round=<?php echo $round; ?>"><button class="button">My Target</button></a>
            <?php }  //End if(isAdmin()) ?>
        </span>
      </div>
      <?php if(isAdmin()){ ?>
      <div>
        <span>
            <a href="players_clear.php?round=<?php echo $round; ?>"><button class="lower-button">Clear Players</button></a>
            <a href="players_reset.php?round=<?php echo $round; ?>"><button class="lower-button">Reset Players</button></a>
            <a href="rules.php?round=<?php echo $round; ?>"><button class="lower-button">Add Rules</button></a>
            <a href="display_rules.php?round=<?php echo $round; ?>"><button class="lower-button">Display Rules</button></a>
        </span>
      </div>
      <div>
        <span>
          <a href="player_add.php?round=<?php echo $round; ?>"><button class="button">Add Player</button></a>
          <?php
            $sql = "SELECT count(player_id) FROM players";
            $num_records = get_value($sql, "count(player_id)");
            if ($num_records > 1)
            {
              echo "<button class='button'>Import File</button>";
            }
            else
            {
              echo "<a href='import_players.php?round=$round'><button class='button'>Import File</button></a>";
            }
          ?>
        </span>
      </div>
    <?php } //End if(isAdmin()) ?>

    </div>
    <br />
    <br />

<?php


  $sql = <<<SQL
            SELECT player_id, player_name, email, player_status
            FROM players
            ORDER BY player_status DESC;
SQL;

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table id='resultsTable' >";
    echo "<tr><th>Name</th>";
    if(isAdmin())
    {
      echo "<th>Email</th>";
    }
    echo "<th>Status</th>";
    if(isAdmin())
    {
      echo "<th>Edit/Delete</th>";
    }
    echo "<th>Player History</th></tr>";

    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["player_name"] . "</td>";
        if(isAdmin())
        {
          echo "<td>" . $row["email"] . "</td>";
        }
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
          echo "Can move on";
        }
        else if ($row["player_status"] == 2)
        {
          echo "Moving on";
        }
        if(isAdmin())
        {
          echo "</td>";
          echo "<td><button onclick='deletePlayer(" . $row["player_id"] . ")'>Delete</button> <button onclick='editPlayer(" . $row["player_id"] . ", $round)'>Edit</button></td></div>";
        }
        echo "<td><button onclick='showHistory(" . $row["player_id"] . ", $round)'>View History</button>";
        echo "</tr>";

    }
}

?>

    </div>
  </body>
</html>
