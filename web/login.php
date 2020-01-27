<?php
/*************************************************************************************************
 * login.php
 *
 * Copyright 2017
 *
 * Login page content. This page is intended to be included in index.php.
 *************************************************************************************************/
include("environment.php");

?>
<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <!-- Bootstrap CSS
 STILL MUST CENTER THE LABEL OVER THE INPUT BLOCKS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
   <title>Login</title>
 </head>
 <body>

      <div class="jumbotron">
          <h2 style="text-align: center">Welcome to Senior Assassin!</h2>
          <form class="justify-content-center" action="javascript:void(0);">
              <div class="col-xs-12" style="height:20px;"></div>
              <div class="form-group">
                  <div class = "row">
                    <label class="col-sm-8 mx-auto" for="email">Email:</label>
                  </div>
                  <div class="col-sm-8 mx-auto">
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email" autofocus />
                  </div>
              </div>
              <div class="form-group">
                  <div class = "row">
                    <label class="col-sm-8 mx-auto" for="password">Password:</label>
                  </div>
                  <div class="col-sm-8 mx-auto">
                      <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                  </div>
              </div>
              <div class="container col-md-6 col-md-offset-3">
                  <input type="submit" id="loginButton" class="btn btn-primary btn-block" value="Log In" onclick="login()" />
              </div>
              <div class="col-xs-12" style="height:10px;"></div>
              <div class= "text-center">
                  Don't have an account? <a class="btn btn-link" href="register.php" role="button">Sign Up</a>
              </div>
          </form>
      </div>
 </head>

<script>

function login() {
    if ($('#email').val() == '') {
        showAlert('danger', 'Email Required!', 'Enter your email address and try again.');
    } else if ($('#password').val() == '') {
        showAlert('danger', 'Password Required!', 'Enter your password and try again.');
    } else {
        var settings = {
            'async': true,
            'url': '../login_api/authenticate.php?email=' + $('#email').val() + '&password=' + $('#password').val(),
            'method': 'POST',
            'headers': {
                'Cache-Control': 'no-cache'
            }
        };

        $('#loginButton').prop('disabled', true);

        $.ajax(settings).done(function(response) {
            alert('Login Successful');
            window.location.replace('index.php');
        }).fail(function(response) {
          alert('Mission Failed. You\'ll get em\' next time');
            showAlert('danger', 'Invalid Login!', 'Check your email address and password and try again.');
        }).always(function() {
            $('#loginButton').prop('disabled', false);
        });
    }
}

function showAlert(type, title, message) {
    alert(message);
}

</script>
