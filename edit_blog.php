<?php
include('header.php');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get blog ID from query
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("Invalid blog ID");
}

// Initialize variables
$title = $content = $media = $mediaType = $author = $email = "";
$error = "";

// Fetch existing blog post
$stmt = $db->prepare("SELECT title, content, media_path, media_type, author, email FROM blog_posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("Blog post not found.");
}
$stmt->bind_result($title, $content, $media, $mediaType, $author, $email);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $mediaType = $_POST['mediaType'] ?? 'image';
    $author = trim($_POST['author'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!$title || !$content || !$author || !$email) {
        $error = "Please fill in all required fields.";
    } else {

        if ($mediaType === 'image') {
            // If user uploaded a new image, process upload
            if (!empty($_FILES['imageFile']['name'])) {
                $targetDir = "uploads/";
                $fileName = basename($_FILES["imageFile"]["name"]);
                $targetFile = $targetDir . uniqid() . "_" . $fileName;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                $check = getimagesize($_FILES["imageFile"]["tmp_name"]);
                if ($check === false) {
                    $error = "File is not an image.";
                } elseif ($_FILES["imageFile"]["size"] > 5 * 1024 * 1024) {
                    $error = "Sorry, your file is too large. Max 5MB.";
                } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
                } else {
                    // Move uploaded file
                    if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $targetFile)) {
                        // Delete old image if exists and was an image
                        if (!empty($media) && file_exists("uploads/" . $media) && $mediaType === 'image') {
                            @unlink("uploads/" . $media);
                        }
                        $media = basename($targetFile);
                    } else {
                        $error = "Sorry, there was an error uploading your file.";
                    }
                }
            }
            // If no new image uploaded, keep old media
        } elseif ($mediaType === 'youtube') {
            // Use the YouTube URL from input
            $newYoutubeUrl = trim($_POST['youtubeUrl'] ?? '');
            if (!filter_var($newYoutubeUrl, FILTER_VALIDATE_URL) ||
                (strpos($newYoutubeUrl, 'youtube.com') === false && strpos($newYoutubeUrl, 'youtu.be') === false)) {
                $error = "Please enter a valid YouTube URL.";
            } else {
                // If old media was an image, delete old image file
                if (!empty($media) && file_exists("uploads/" . $media) && $mediaType === 'image') {
                    @unlink("uploads/" . $media);
                }
                $media = $newYoutubeUrl;
            }
        }

        if (!$error) {
            // Update DB (fix bind_param order!)
            $stmt = $db->prepare("UPDATE blog_posts SET title=?, content=?, media_type=?, media_path=?, author=?, email=? WHERE id=?");
            $stmt->bind_param("ssssssi", $title, $content, $mediaType, $media, $author, $email, $id);

            if ($stmt->execute()) {
                echo "<script>alert('Blog updated successfully'); window.location.href='vblog.php';</script>";
                exit;
            } else {
                $error = "Database error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">

<section class="w3-content w3-white w3-padding w3-card" style="max-width: 800px; margin:auto; margin-top: 20px;">
  <h2 class="mb-4">Edit Blog Post</h2>

  <?php if ($error): ?>
    <div class="w3-panel w3-red w3-round"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form id="blogForm" method="POST" enctype="multipart/form-data">

    <label>Blog Title</label>
    <input class="w3-input w3-border w3-round" type="text" name="title" required value="<?= htmlspecialchars($title) ?>">

    <label class="mt-3">Content</label>
    <textarea class="w3-input w3-border w3-round" name="content" rows="6" required><?= htmlspecialchars($content) ?></textarea>

    <label class="mt-3">Media Type</label><br>
    <input type="radio" name="mediaType" value="image" <?= ($mediaType === 'image') ? 'checked' : '' ?> onclick="toggleMedia()"> Image Upload<br>
    <input type="radio" name="mediaType" value="youtube" <?= ($mediaType === 'youtube') ? 'checked' : '' ?> onclick="toggleMedia()"> YouTube Link

    <div id="imageUpload" class="mt-2" style="<?= $mediaType === 'image' ? '' : 'display:none;' ?>">
      <label>Upload Image</label>
      <input class="w3-input w3-border w3-round" type="file" name="imageFile" accept="image/*">
      <?php if ($mediaType === 'image' && !empty($media)): ?>
        <img src="uploads/<?= htmlspecialchars($media) ?>" class="w3-margin-top" style="max-height: 100px;">
      <?php endif; ?>
    </div>

    <div id="youtubeInput" class="mt-2" style="<?= $mediaType === 'youtube' ? '' : 'display:none;' ?>">
      <label>YouTube URL</label>
      <input class="w3-input w3-border w3-round" type="url" name="youtubeUrl" placeholder="https://www.youtube.com/watch?v=..." value="<?= $mediaType === 'youtube' ? htmlspecialchars($media) : '' ?>">
    </div>

    <label class="mt-3">Author Name</label>
    <input class="w3-input w3-border w3-round" type="text" name="author" required value="<?= htmlspecialchars($author) ?>">

    <label class="mt-3">Email</label>
    <input class="w3-input w3-border w3-round" type="email" name="email" required value="<?= htmlspecialchars($email) ?>">

    <button type="submit" class="w3-button w3-blue w3-round w3-margin-top">Update Blog Post</button>
  </form>
</section>

<script>
  function toggleMedia() {
    const type = document.querySelector('input[name="mediaType"]:checked').value;
    document.getElementById('imageUpload').style.display = (type === 'image') ? 'block' : 'none';
    document.getElementById('youtubeInput').style.display = (type === 'youtube') ? 'block' : 'none';
  }

  // Run on page load
  toggleMedia();
</script>

 </div><!-- /.content-wrapper -->
   
    </div><!-- ./wrapper -->

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