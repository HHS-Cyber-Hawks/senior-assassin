<?php

/*************************************************************************************************
 * authenticate.php
 *
 * Copyright 2018
 *
 * Authenticates the user based on the provided email address and password (sent as request
 * parameters).
 *
 * - email      the user's email address
 * - password   the user's password
 *
 * This page returns the following HTTP status codes:
 *
 * - 200 if the credentials were authenticated successfully
 * - 401 if the credentials could not be authenticated
 *************************************************************************************************/

require_once '../web/environment.php';
$dbh = create_connection();

$email = mysqli_real_escape_string($dbh, $email);
$password = mysqli_real_escape_string($dbh, $password);

$sql = <<<SQL
SELECT id, display_name
  FROM users
 WHERE email = '{$email}'
   AND password = PASSWORD('{$password}')
SQL;


$result = $dbh->query($sql);

$count = $result->num_rows;
if ($count == 1)
{
    $row = $result->fetch_assoc();

    session_start();

    $_SESSION['userId'] = $row['id'];
    $_SESSION['displayName'] = $row['display_name'];
    $_SESSION['authenticated'] = true;

    session_write_close();
    http_response_code(200);
}
else
{
    http_response_code(401);
}
