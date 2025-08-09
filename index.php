<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>AdminLTE 2 | Log in</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link
      href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"
      rel="stylesheet"
      type="text/css"
    />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>Admin</b>LTE</a>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form id="loginForm" method="post">
          <div class="form-group has-feedback">
            <input
              type="text"
              class="form-control"
              placeholder="Username"
              name="username"
              id="username"
              required
            />
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input
              type="password"
              class="form-control"
              placeholder="Password"
              name="password"
              id="password"
              required
            />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div id="error-msg" style="color:red; margin-bottom:10px;"></div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember" /> Remember Me
                </label>
              </div>
            </div>
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">
                Sign In
              </button>
            </div>
          </div>
        </form>

        <a href="#">I forgot my password</a><br />
      </div>
    </div>

    <!-- jQuery 2.1.3 -->
    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>

    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });

        // AJAX form submission
        $('#loginForm').on('submit', function (e) {
          e.preventDefault();
          $('#error-msg').text(''); // clear previous error

          $.ajax({
            url: 'login.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
              if (response.success) {
                // Redirect to dashboard
                window.location.href = 'dashboard.php';
              } else {
                // Show error message
                $('#error-msg').text(response.message);
              }
            },
            error: function () {
              $('#error-msg').text('An error occurred. Please try again.');
            }
          });
        });
      });
    </script>
  </body>
</html>
