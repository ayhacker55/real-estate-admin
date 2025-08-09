<?php
include('header.php');

// Assume $username is available from session
$username = $_SESSION['username'] ?? null;
if (!$username) {
    die("Username not set in session.");
}

// Connect to DB (assuming $db is already initialized in header.php)

// Fetch admin info
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
<section class="w3-content w3-white w3-padding w3-card" style="max-width: 800px; margin:auto; margin-top: 20px;">
  <h2 class="mb-4">Edit Admin Profile</h2>

  <form id="editAdminForm" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $admin['id'] ?>">
    <input type="hidden" name="existing_photo" value="<?= htmlspecialchars($admin['profile_photo']) ?>">

    <label>Surname</label>
    <input class="w3-input w3-border w3-round" type="text" name="surname" value="<?= htmlspecialchars($admin['surname']) ?>" required>

    <label class="mt-3">Last Name</label>
   <input class="w3-input w3-border w3-round" type="text" name="surname" value="<?= htmlspecialchars($admin['surname']) ?>" required>

    <label class="mt-3">Email</label>
    <input class="w3-input w3-border w3-round" type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>

    <label class="mt-3">Username</label>
    <input class="w3-input w3-border w3-round" type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" readonly>

    <label class="mt-3">Role</label>
    <select class="w3-select w3-border w3-round" name="role" required>
      <option value="super_admin" <?= $admin['role'] === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
      <option value="sub_admin" <?= $admin['role'] === 'sub_admin' ? 'selected' : '' ?>>Sub Admin</option>
    </select>

    <!-- Profile Photo -->
    <label class="mt-3">Profile Photo</label>
    <input class="w3-input w3-border w3-round" type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" onchange="previewPhoto()">

    <div id="photoPreviewContainer" class="w3-margin-top" style="<?= empty($admin['   profile_photo']) ? 'display: none;' : '' ?>">
      <img id="photoPreview"
           src="<?= !empty($admin['   profile_photo']) ? htmlspecialchars($admin['  profile_photo']) : '' ?>"
           alt="Profile Photo"
           style="max-width: 150px; height: auto; border: 1px solid #ccc; padding: 5px;">
      <br>
      <button type="button" class="w3-button w3-red w3-small w3-round w3-margin-top" onclick="removePhoto()">Remove Photo</button>
    </div>

    <button type="submit" class="w3-button w3-blue w3-round w3-margin-top">Update Admin</button>
  </form>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function previewPhoto() {
    const file = document.getElementById('profilePhotoInput').files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.getElementById('photoPreview');
        img.src = e.target.result;
        document.getElementById('photoPreviewContainer').style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  }

  function removePhoto() {
    document.getElementById('profilePhotoInput').value = '';
    document.getElementById('photoPreview').src = '';
    document.getElementById('photoPreviewContainer').style.display = 'none';
  }

  $('#editAdminForm').on('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    $.ajax({
      url: 'update_admin.php',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        alert(response);
        // Optionally reload to reflect changes
        location.reload();
      },
      error: function (xhr) {
        alert("Server error: " + xhr.responseText);
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