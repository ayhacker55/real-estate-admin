<?php include('header.php')?>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">

<section class="w3-content w3-white w3-padding w3-card" style="max-width: 800px; margin:auto; margin-top: 20px;">
  <h2 class="mb-4">Submit a Blog Post</h2>
  <form id="blogForm" enctype="multipart/form-data">

    <label>Blog Title</label>
    <input class="w3-input w3-border w3-round" type="text" name="title" required>

    <label class="mt-3">Content</label>
    <textarea class="w3-input w3-border w3-round" name="content" rows="6" required></textarea>

    <label class="mt-3">Media Type</label><br>
    <input type="radio" name="mediaType" value="image" checked onclick="toggleMedia()"> Image Upload<br>
    <input type="radio" name="mediaType" value="youtube" onclick="toggleMedia()"> YouTube Link

    <div id="imageUpload" class="mt-2">
      <label>Upload Image</label>
      <input class="w3-input w3-border w3-round" type="file" name="imageFile" accept="image/*">
    </div>

    <div id="youtubeInput" class="mt-2" style="display:none;">
      <label>YouTube URL</label>
      <input class="w3-input w3-border w3-round" type="url" name="youtubeUrl" placeholder="https://www.youtube.com/watch?v=...">
    </div>

    <label class="mt-3">Author Name</label>
    <input class="w3-input w3-border w3-round" type="text" name="author" required>

    <label class="mt-3">Email</label>
    <input class="w3-input w3-border w3-round" type="email" name="email" required>

    <button type="submit" class="w3-button w3-blue w3-round w3-margin-top">Submit Blog Post</button>
  </form>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function toggleMedia() {
    const type = document.querySelector('input[name="mediaType"]:checked').value;
    document.getElementById('imageUpload').style.display = (type === 'image') ? 'block' : 'none';
    document.getElementById('youtubeInput').style.display = (type === 'youtube') ? 'block' : 'none';
  }

  $('#blogForm').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    $.ajax({
      url: 'upload_blog.php',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function (response) {
        alert(response);
        $('#blogForm')[0].reset();
        toggleMedia();
      },
      error: function (xhr, status, error) {
  alert("Server response: " + xhr.responseText);
}

    });
  });
</script>




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