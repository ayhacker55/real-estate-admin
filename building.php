<?php include('header.php')?>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">


<?php
// Connect to database
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

// Get categories for the dropdown
$query = "SELECT id, cate FROM category2 ORDER BY 1";
$result = mysqli_query($db, $query);
?>

<section class="w3-content w3-white w3-padding w3-card" style="max-width: 800px; margin:auto; margin-top: 20px;">
  <h2 class="mb-4">Upload House for Sale</h2>
  <form id="propertyForm" method="POST" enctype="multipart/form-data">

    <!-- Title -->
    <label class="form-label">Title</label>
    <input class="w3-input w3-border w3-round" type="text" name="title" placeholder="Listing Title" required />

    <!-- Property Type -->
    <label class="form-label mt-3">Property Type</label>
    <select class="w3-select w3-border w3-round" name="type" required>
      <option value="" disabled selected>Select Property Type</option>
      <?php while ($row = mysqli_fetch_assoc($result)) {
        echo "<option>" . htmlspecialchars($row['cate']) . "</option>";
      } ?>
    </select>

    <!-- Listing Type -->
    <label class="form-label mt-3">Listing Type</label>
    <select class="w3-select w3-border w3-round" name="listingType" required>
      <option>For Sale</option>
      <option>For Rent</option>
    </select>

    <!-- Price -->
    <label class="form-label mt-3">Price</label>
    <input class="w3-input w3-border w3-round" type="number" name="price" placeholder="Price" min="0" required />

    <!-- Status -->
    <label class="form-label mt-3">Status</label>
    <select class="w3-select w3-border w3-round" name="status" required>
      <option>Available</option>
      <option>Sold</option>
      <option>Pending</option>
    </select>



    <!-- Location -->
    <label class="form-label mt-3">State</label>
    <select class="w3-select w3-border w3-round" name="state" required>
      <option value="" disabled selected>Select State</option>
      <option value="Abia">Abia</option>
      <option value="Adamawa">Adamawa</option>
      <option value="Akwa Ibom">Akwa Ibom</option>
      <option value="Anambra">Anambra</option>
      <option value="Bauchi">Bauchi</option>
      <option value="Bayelsa">Bayelsa</option>
      <option value="Benue">Benue</option>
      <option value="Borno">Borno</option>
      <option value="Cross River">Cross River</option>
      <option value="Delta">Delta</option>
      <option value="Ebonyi">Ebonyi</option>
      <option value="Edo">Edo</option>
      <option value="Ekiti">Ekiti</option>
      <option value="Enugu">Enugu</option>
      <option value="FCT">Federal Capital Territory (FCT)</option>
      <option value="Gombe">Gombe</option>
      <option value="Imo">Imo</option>
      <option value="Jigawa">Jigawa</option>
      <option value="Kaduna">Kaduna</option>
      <option value="Kano">Kano</option>
      <option value="Katsina">Katsina</option>
      <option value="Kebbi">Kebbi</option>
      <option value="Kogi">Kogi</option>
      <option value="Kwara">Kwara</option>
      <option value="Lagos">Lagos</option>
      <option value="Nasarawa">Nasarawa</option>
      <option value="Niger">Niger</option>
      <option value="Ogun">Ogun</option>
      <option value="Ondo">Ondo</option>
      <option value="Osun">Osun</option>
      <option value="Oyo">Oyo</option>
      <option value="Plateau">Plateau</option>
      <option value="Rivers">Rivers</option>
      <option value="Sokoto">Sokoto</option>
      <option value="Taraba">Taraba</option>
      <option value="Yobe">Yobe</option>
      <option value="Zamfara">Zamfara</option>
    </select>

    <label class="form-label mt-3">City / Location</label>
    <input class="w3-input w3-border w3-round" type="text" name="city" placeholder="City or Location" required />

    <label class="form-label mt-3">Street Address</label>
    <input class="w3-input w3-border w3-round" type="text" name="street" placeholder="Street Address" required />

    <!-- Photo Inputs container -->
    <label class="form-label mt-3">Gallery Photos</label>
    <div id="photoInputs" class="mb-3"></div>

    <!-- Preview container -->
    <div id="galleryPreview" class="row g-2 mb-3"></div>

    <button type="button" class="w3-button w3-light-grey w3-round" onclick="addPhotoInput()">Add Another Photo</button>

    <!-- Description -->
    <label class="form-label mt-3">Property Description</label>
    <textarea class="w3-input w3-border w3-round" name="description" rows="5" placeholder="Describe the property..." required></textarea>

    <!-- Contact Info -->
    <label class="form-label mt-3">Contact Name</label>
    <input class="w3-input w3-border w3-round" type="text" name="contactName" placeholder="Your Name" required />

    <label class="form-label mt-3">Phone Number</label>
    <input class="w3-input w3-border w3-round" type="tel" name="phone" placeholder="Phone Number" required />

    <label class="form-label mt-3">Email Address</label>
    <input class="w3-input w3-border w3-round" type="email" name="email" placeholder="Email Address" required />

    <button type="submit" class="w3-button w3-blue w3-round w3-margin-top">Submit Listing</button>

  </form>
</section>

<!-- JQuery CDN for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  let photoId = 0;

  function addPhotoInput() {
    const inputContainer = document.getElementById('photoInputs');
    const previewContainer = document.getElementById('galleryPreview');

    const id = 'photo-' + photoId++;

    // Create file input group
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('d-flex', 'align-items-center', 'gap-2', 'mb-2');
    inputGroup.setAttribute('data-id', id);

    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'galleryPhotos[]';
    input.accept = 'image/*';
    input.classList.add('form-control');
    input.required = true;

    input.onchange = function (e) {
      const file = e.target.files[0];
      if (file) {
        // Remove existing preview for this input if any
        const existing = previewContainer.querySelector(`[data-id="${id}"]`);
        if (existing) existing.remove();

        const imgWrapper = document.createElement('span');
        imgWrapper.classList.add('col', 'position-relative');
        imgWrapper.style.display = 'inline-block';
        imgWrapper.style.marginRight = '10px';
        imgWrapper.setAttribute('data-id', id);

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.classList.add('img-thumbnail');
        img.style.maxHeight = '150px';

        // Delete button over image
        const delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.innerHTML = '&times;';
        delBtn.classList.add('btn', 'btn-sm', 'btn-danger', 'position-absolute', 'top-0', 'end-0');
        delBtn.style.zIndex = '10';
        delBtn.style.fontWeight = 'bold';
        delBtn.style.padding = '0 6px';
        delBtn.onclick = () => {
          inputGroup.remove();
          imgWrapper.remove();
        };

        imgWrapper.appendChild(img);
        imgWrapper.appendChild(delBtn);
        previewContainer.appendChild(imgWrapper);
      }
    };

    inputGroup.appendChild(input);
    inputContainer.appendChild(inputGroup);
  }

  // Add one photo input on load
  window.onload = addPhotoInput;

  // AJAX form submit
  $(document).ready(function () {
    $('#propertyForm').on('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      $.ajax({
        url: 'upload2.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          if (response.toLowerCase().includes('success')) {
            alert(response);
            $('#propertyForm')[0].reset();
            $('#galleryPreview').empty();
            $('#photoInputs').empty();
            addPhotoInput();
          } else {
            alert('Error: ' + response);
          }
        },
        error: function (xhr, status, error) {
          alert('Something went wrong: ' + error);
        }
      });
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