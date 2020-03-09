<?php


include("environment.php");

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

      <div class="jumbotron">
          <h2 style="text-align: center">Registration</h2>

          <p style="text-align: center">Enter the information below to create your account.</p>

          <form class="form-horizontal" action="javascript:void(0)">
              <div class="col-xs-12" style="height:20px;"></div>
              <div class="form-group">
                <div class = "row">
                  <label class="col-sm-8 mx-auto" for="name">Name:</label>
                </div>
                  <div class="col-sm-8 mx-auto">
                      <input type="name" class="form-control" id="name" name="name" placeholder="Name" />
                  </div>
              </div>
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
              <div class="form-group">
                <div class = "row">
                  <label class="col-sm-8 mx-auto" from="confirmPassword">Confirm Password:</label>
                </div>
                  <div class="col-sm-8 mx-auto">
                      <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
                  </div>
              </div>
              <div class="form-group">
                <div class = "row">
                  <label class="col-sm-8 mx-auto" for="displayName">Display Name:</label>
                </div>
                  <div class="col-sm-8 mx-auto">
                      <input type="text" class="form-control" id="displayName" name="displayName" placeholder="Display Name" />
                  </div>
              </div>
              <div class="container col-md-6 col-md-offset-3">
                  <input type="button" id="registerButton" class="btn btn-primary btn-block" value="Create Account" onclick="register()" />
              </div>
              <div class="col-xs-12" style="height:30px;"></div>
              <div class="text-center">
                  <a  href="login.php?" role="button">Return to the login page</a>
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
          } else if ($('#name').val() == '') {
                showAlert('danger', 'Name Required!', 'Your name can\'t be blank. Enter a value and try again.');
          } else {
              var settings = {
                  'async': true,
                  'url': '../login_api/createAccount.php?email=' + $('#email').val() + '&password=' + $('#password').val() + '&displayName=' + $('#displayName').val() + '&is_admin=0' + '&name=' + $('#name').val(),
                  'method': 'POST',
                  'headers': {
                      'Cache-Control': 'no-cache'
                  }
              };

              $('#registerButton').prop('disabled', true);

              $.ajax(settings).done(function(response) {
                  showAlert('success', 'Account Registered!', 'Continue to the login page to get started.');
                  setTimeout(function() { window.location.replace('login.php'); }, 5000);
              }).fail(function(jqXHR) {
                  if (jqXHR.status == 400) {
                      showAlert('danger', 'Email Taken!', 'This email address has been used already.');
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
