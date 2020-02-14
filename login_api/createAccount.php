<?php

/*************************************************************************************************
 * createAccount.php
 *
 * Copyright 2017-2018
 *
 * Creates a user account. This page expects the following request parameters to be present:
 *
 * - email          the user's email address
 * - displayName    the user's display name
 * - password       the user's password
 *
 * This page returns the following HTTP status codes:
 *
 * - 200 if the account was created successfully
 * - 400 if the given email address is already in use
 * - 500 if the account could not be created for some other reason
 *************************************************************************************************/

require_once '../web/environment.php';
$dbh = create_connection();

$email = mysqli_real_escape_string($dbh, $email);
$password = mysqli_real_escape_string($dbh, $password);
$displayName = mysqli_real_escape_string($dbh, $displayName);

$sql = <<<SQL
SELECT id
  FROM users
 WHERE email = '{$email}'
SQL;

$result = $dbh->query($sql);

$count = $result->num_rows;
if ($count == 0)
{
    $sql = <<<SQL
    INSERT INTO users (user_name, email, password, display_name, has_paid, is_admin)
    VALUES ('{$name}','{$email}', PASSWORD('{$password}'), '{$displayName}', 0, 0)
SQL;

    if ($dbh->query($sql))
    {
        http_response_code(200);
    }
    else
    {
        http_response_code(500);
    }
}
else
{
    // Email already in use
    http_response_code(400);
}
