<?php
include('header.php');

$username = $_SESSION['username'] ?? null;
if (!$username) {
    die("Username not set in session.");
}

$stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Admin not found.");
}

$admin = $result->fetch_assoc();
?>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<section class="w3-content w3-white w3-padding w3-card" style="max-width: 400px; margin:auto; margin-top: 20px;">
  <h2>Change Password</h2>

  <form id="changePasswordForm">
    <input type="hidden" name="id" value="<?= $admin['id'] ?>">

    <label>Old Password</label>
    <div class="w3-row w3-margin-bottom" style="position: relative;">
      <input class="w3-input w3-border w3-round" type="password" name="old_password" id="oldPassword" required>
      <i class="fa-solid fa-eye-slash" id="toggleOld" onclick="togglePassword('oldPassword', 'toggleOld')" style="position: absolute; right: 10px; top: 12px; cursor: pointer;"></i>
    </div>

    <label>New Password</label>
    <div class="w3-row w3-margin-bottom" style="position: relative;">
      <input class="w3-input w3-border w3-round" type="password" name="new_password" id="newPassword" required>
      <i class="fa-solid fa-eye-slash" id="toggleNew" onclick="togglePassword('newPassword', 'toggleNew')" style="position: absolute; right: 10px; top: 12px; cursor: pointer;"></i>
    </div>

    <button type="submit" class="w3-button w3-blue w3-round w3-margin-top">Update Password</button>
  </form>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function togglePassword(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon = document.getElementById(iconId);

  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  } else {
    input.type = 'password';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  }
}

$('#changePasswordForm').on('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);

  $.ajax({
    url: 'update_password.php',
    method: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    success: function(response) {
      alert(response);
      if (response.toLowerCase().includes('success')) {
        $('#changePasswordForm')[0].reset();
        $('#oldPassword').attr('type', 'password');
        $('#newPassword').attr('type', 'password');
        $('#toggleOld').removeClass('fa-eye').addClass('fa-eye-slash');
        $('#toggleNew').removeClass('fa-eye').addClass('fa-eye-slash');
      }
    },
    error: function(xhr) {
      alert('Server error: ' + xhr.responseText);
    }
  });
});
</script>

 </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights reserved.
      </footer>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.3 -->
    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- jQuery UI 1.11.2 -->
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>    
    <!-- Morris.js charts -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/knob/jquery.knob.js" type="text/javascript"></script>
    <!-- daterangepicker -->
    <script src="plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <!-- Slimscroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js" type="text/javascript"></script>

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js" type="text/javascript"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js" type="text/javascript"></script>
  </body>
</html>