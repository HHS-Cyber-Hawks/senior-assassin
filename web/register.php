<?php

//// TODO: Figure out css of the register button
include("environment.php");

$conn = create_connection();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

   <title>Hello, world!</title>
 </head>
 <body>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

      <div class="jumbotron col-md-8 col-md-offset-2">
          <h2>Registration</h2>

          <p>Enter the information below to create your account.</p>

          <form class="form-horizontal" action="javascript:void(0)">
              <div class="col-xs-12" style="height:20px;"></div>
              <div class="form-group">
                  <label class="col-sm-3 control-label" for="email">Email:</label>
                  <div class="col-sm-9">
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email" autofocus />
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-3 control-label" for="password">Password:</label>
                  <div class="col-sm-9">
                      <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-3 control-label" from="confirmPassword">Confirm Password:</label>
                  <div class="col-sm-9">
                      <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-3 control-label" for="displayName">Display Name:</label>
                  <div class="col-sm-9">
                      <input type="text" class="form-control" id="displayName" name="displayName" placeholder="Display Name" />
                  </div>
              </div>
              <div class="container col-md-6 col-md-offset-3">
                  <input type="button" id="registerButton" class="btn btn-primary btn-block" value="Create Account" onclick="register()" />
              </div>
              <div class="col-xs-12" style="height:30px;"></div>
              <div class="container col-md-6 col-md-offset-3">
                  <a  href="index.php?content=login" role="button">Return to the login page</a>
              </div>
          </form>
      </div>

      <script>

      function register() {
          if ($('#email').val() == '') {
              showAlert('danger', 'Email Required!', 'Enter your email address and try again!');
          } else if ($('#password').val() == '') {
              showAlert('danger', 'Password Required!', 'Enter your password and try again!');
          } else if ($('#password').val() != $('#confirmPassword').val()) {
              showAlert('danger', 'Password Mismatch!', 'Your passwords don\'t match, try again.');
          } else if ($('#displayName').val() == '') {
                  showAlert('danger', 'Display Name Required!', 'Your display name can\'t be blank. Enter a value and try again.');
          } else {
              var settings = {
                  'async': true,
                  'url': '../login_api/createAccount.php?email=' + $('#email').val() + '&password=' + $('#password').val() + '&displayName=' + $('#displayName').val() + '&is_admin=0',
                  'method': 'POST',
                  'headers': {
                      'Cache-Control': 'no-cache'
                  }
              };

              $('#registerButton').prop('disabled', true);

              $.ajax(settings).done(function(response) {
                  showAlert('success', 'Account Registered!', 'Continue to the <a href="index.php?content=login">login page</a> to get started.');
                  setTimeout(function() { window.location.replace('index.php?content=login'); }, 5000);
              }).fail(function(jqXHR) {
                  if (jqXHR.status == 400) {
                      showAlert('danger', 'Email Taken!', 'This email address has been used already. Do you need to <a href="index.php?content=passwordRecovery">reset the password</a>?');
                  } else {
                      showAlert('danger', 'Oops, Error!', 'Something went wrong, try again later.');
                  }
              }).always(function() {
                  $('#registerButton').prop('disabled', false);
              });
          }
      }

      function showAlert(type, title, message) {
          alert(message);
      }

      </script>
</body>
