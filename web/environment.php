<?php
  // PLAYER STATUS KEY
  # -1  = out
  # 0 = playing
  # 1 = can move on

  // ASSIGNMENT STATUS KEY
  # 0 = open
  # 1 = pending
  # 2 = confirmed
  # 3 = obsolete

  //EMAIl STUFF
  # email - seniorassassinemail@gmail.
  # password - seniorassassin!
  require 'vendor/autoload.php';

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  extract($_REQUEST);

  //Set info on user session (player is admin?)

  session_start();
  $conn = create_connection();

// If you aren't logged in and you try to access a page, it redirects you to the login page
  if((!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) && !allowedForLogin($_SERVER['REQUEST_URI'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
  }

  function allowedForLogin($URI) {
    $listOfAllowed = ['login.php', 'logout.php', 'register.php', 'authenticate.php', 'createAccount.php', 'resetPassword.php'];
    $inList = false;
    foreach ($listOfAllowed as $url)
    {
      if (strpos($URI, $url))
      {
        $inList = true;
      }
    }
    return $inList;
  }

// If you aren't an admin and try to access an admin page, you get redirected back to index.php
  if(!isAdmin() && !allowedForPlayerUse($_SERVER['REQUEST_URI'])) {
    header("Location: index.php?round=1");
    die("Redirecting to index.php?round=1");
  }

  function allowedForPlayerUse($URI) {
    $listOfAllowed = ['login.php', 'logout.php', 'register.php',
                      'authenticate.php', 'createAccount.php', 'resetPassword.php',
                      'display_rules.php', 'feed.php', 'index.php',
                      'see_target.php', 'player_history.php', 'rules.php'];
    $inList = false;
    foreach ($listOfAllowed as $url)
    {
      if (strpos($URI, $url))
      {
        $inList = true;
      }
    }
    return $inList;
  }



  function isAdmin()
  {
    if(isset($_SESSION["admin"]))
    {
      return $_SESSION["admin"];
    }
    else
    {
      return false;
    }
  }

  function create_connection()
  {
    $servername = "mysql.server295.com";
    $username = "assassin";
    $password = "billiard gale seeing";
    $dbname = "passingf_assassin";

    // Create connection
    return new mysqli($servername, $username, $password, $dbname);
  }

  // for parameters you pass in the actual query you want int SQL and value is the actual value you are selecting
  function get_value($query, $value) {
    $conn = create_connection();
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row["$value"];
  }

  function verify_authentication()
  {
      global $config, $content, $unauthenticatedContents, $unauthenticatedScripts;

      $scriptRoot = $config['general']['script_root'];
      $scriptName = substr($_SERVER['PHP_SELF'], strlen($scriptRoot));

      if (!(($scriptName == 'index.php' && in_array($content, $unauthenticatedContents)) ||
            in_array($scriptName, $unauthenticatedScripts)))
      {
          session_start();

          if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated'])
          {
              session_unset();
              session_destroy();

              header('Location: login.php');

              exit();
          }

          session_write_close();
      }
    }

    function send_email($recipient, $subject, $message)
    {
        $mail = new PHPMailer;
        $mail->isSMTP();

        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';

        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;

        $mail->Username = 'seniorassassinemail@gmail.com';
        $mail->Password = 'seniorassassin!';

        $mail->setFrom('seniorassassinemail@gmail.com', 'Senior Assassin No-Reply');

        // $mail->addAddress($recipient);
        $mail->addAddress('lbertoni20@hanoverstudents.org');
        $mail->Subject = $subject;
        $mail->msgHTML($message);

        $mail->send();
    }

 ?>
