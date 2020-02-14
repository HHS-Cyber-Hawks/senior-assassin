<?php include("environment.php");
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
            <a href="assignment_display.php?round=1"><button class="button">Assignments</button></a>
            <a href="approve_players.php"><button class="button">Yet to Pay</button></a>
            <?php } else {  //if(isAdmin()) ?>
            <a href="see_target.php?round=<?php echo $round; ?>"><button class="button">My Target</button></a>
            <?php }  //End if(isAdmin()) ?>
        </span>
      </div>
</html>
<?php

$sql = <<<SQL
            SELECT user_name, email, display_name, has_paid, is_admin
            FROM users
            ORDER BY user_name
SQL;

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table id='resultsTable' >";
    echo "<tr><th>Name</th>";
    echo "<th>Email</th>";
    echo "<th>Display Name</th>";
    echo "<th>Paid Status</th>";

    while($row = $result->fetch_assoc()) {
        if($row["is_admin"] == 0 && $row["has_paid"] == 0)
        {
          echo "<tr>";
          echo "<td>";
          echo $row["user_name"];
          echo "</td>";
          echo "<td>";
          echo $row["email"];
          echo "</td>";
          echo "<td>";
          echo $row["display_name"];
          echo "</td>";
          echo "<td>";
          echo "<button> Has Paid </button>";
          echo "</td>";
          echo "</tr>";
        }

    }
}

?>
