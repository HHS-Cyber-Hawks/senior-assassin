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
  extract($_REQUEST);

  //Set info on user session (player is admin?)

  session_start();

  function isAdmin()
  {
    return $_SESSION["admin"];
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

              header('Location: index.php');

              exit();
          }

          session_write_close();
      }
    }

    function send_email($recipient, $subject, $message)
    {
        global $config;

        $mail = new PHPMailer;
        $mail->isSMTP();

        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';

        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;

        $mail->Username = 'jheyl20@hanoverstudents.org';
        $mail->Password = '905-3064';

        $mail->setFrom($config['gmail']['email'], $config['gmail']['name']);

        $mail->addAddress($recipient);
        $mail->Subject = $subject;
        $mail->msgHTML($message);

        $mail->send();
    }


 ?>
