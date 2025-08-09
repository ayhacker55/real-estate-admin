<?php
include('header.php');
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

// Fetch categories for dropdown
$query = "SELECT id, cate FROM category2 ORDER BY 1";
$categoryResult = mysqli_query($db, $query);

// Initialize variables for the form
$id = $title = $type = $listingType = $price = $status = $state = $city = $street = "";
$description = $contactName = $phone = $email = $galleryPhotos = $publish = "";
$galleryPhotosArr = [];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT id, title, type, listingType, price, status, state, city, street, description, contactName, phone, email, galleryPhotos, publish FROM decoration WHERE id = $id LIMIT 1";
    $res = mysqli_query($db, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $title = htmlspecialchars($row['title']);
        $type = $row['type'];
        $listingType = $row['listingType'];
        $price = $row['price'];
        $status = $row['status'];
        $state = $row['state'];
        $city = htmlspecialchars($row['city']);
        $street = htmlspecialchars($row['street']);
        $description = htmlspecialchars($row['description']);
        $contactName = htmlspecialchars($row['contactName']);
        $phone = htmlspecialchars($row['phone']);
        $email = htmlspecialchars($row['email']);
        $publish = $row['publish'];
        $galleryPhotos = $row['galleryPhotos'];

        // Decode JSON gallery photos into an array
        if ($galleryPhotos) {
            $galleryPhotosArr = json_decode($galleryPhotos, true);
            if (!is_array($galleryPhotosArr)) {
                $galleryPhotosArr = [];
            }
        }
    }
}
?>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">

<section class="w3-content w3-white w3-padding w3-card" style="max-width: 800px; margin:auto; margin-top: 20px;">
  <h2 class="mb-4"><?php echo $id ? "Edit" : "Add"; ?> Building Decoration Listing</h2>
  <form id="propertyForm" method="POST" enctype="multipart/form-data" action="save_decoration.php">

    <?php if ($id): ?>
      <input type="hidden" name="id" value="<?php echo $id; ?>">
    <?php endif; ?>

    <!-- Title -->
    <label class="form-label">Title</label>
    <input class="w3-input w3-border w3-round" type="text" name="title" placeholder="Listing Title" value="<?php echo $title; ?>" required />

    <!-- Property Type -->
    <label class="form-label mt-3">Property Type</label>
    <select class="w3-select w3-border w3-round" name="type" required>
      <option value="" disabled <?php echo !$type ? "selected" : ""; ?>>Select Property Type</option>
      <?php 
      // Reset pointer to loop categories again
      mysqli_data_seek($categoryResult, 0);
      while ($row = mysqli_fetch_assoc($categoryResult)) {
        $selected = ($type == $row['cate']) ? "selected" : "";
        echo "<option value=\"" . htmlspecialchars($row['cate']) . "\" $selected>" . htmlspecialchars($row['cate']) . "</option>";
      } 
      ?>
    </select>

    <!-- Listing Type -->
    <label class="form-label mt-3">Listing Type</label>
    <select class="w3-select w3-border w3-round" name="listingType" required>
      <option value="For Sale" <?php if ($listingType == 'For Sale') echo 'selected'; ?>>For Sale</option>
      <option value="For Rent" <?php if ($listingType == 'For Rent') echo 'selected'; ?>>For Rent</option>
    </select>

    <!-- Price -->
    <label class="form-label mt-3">Price</label>
    <input class="w3-input w3-border w3-round" type="number" name="price" placeholder="Price" min="0" value="<?php echo $price; ?>" required />

    <!-- Status -->
    <label class="form-label mt-3">Status</label>
    <select class="w3-select w3-border w3-round" name="status" required>
      <option value="Available" <?php if ($status == 'Available') echo 'selected'; ?>>Available</option>
      <option value="Sold" <?php if ($status == 'Sold') echo 'selected'; ?>>Sold</option>
      <option value="Pending" <?php if ($status == 'Pending') echo 'selected'; ?>>Pending</option>
    </select>

    <!-- Location -->
    <label class="form-label mt-3">State</label>
    <select class="w3-select w3-border w3-round" name="state" required>
      <option value="" disabled <?php echo !$state ? "selected" : ""; ?>>Select State</option>
      <?php
      $states = ["Abia","Adamawa","Akwa Ibom","Anambra","Bauchi","Bayelsa","Benue","Borno","Cross River","Delta","Ebonyi","Edo","Ekiti","Enugu","FCT","Gombe","Imo","Jigawa","Kaduna","Kano","Katsina","Kebbi","Kogi","Kwara","Lagos","Nasarawa","Niger","Ogun","Ondo","Osun","Oyo","Plateau","Rivers","Sokoto","Taraba","Yobe","Zamfara"];
      foreach ($states as $st) {
        $sel = ($state == $st) ? "selected" : "";
        echo "<option value=\"$st\" $sel>$st</option>";
      }
      ?>
    </select>

    <label class="form-label mt-3">City / Location</label>
    <input class="w3-input w3-border w3-round" type="text" name="city" placeholder="City or Location" value="<?php echo $city; ?>" required />

    <label class="form-label mt-3">Street Address</label>
    <input class="w3-input w3-border w3-round" type="text" name="street" placeholder="Street Address" value="<?php echo $street; ?>" required />

    <!-- Gallery Photos -->
    <label class="form-label mt-3">Gallery Photos</label>
    <div id="photoInputs" class="mb-3"></div>
        <button type="button" class="w3-button w3-light-grey w3-round" onclick="addPhotoInput()">Add Another Photo</button>

    <div id="galleryPreview" class="w3-row-padding mb-3">
      <?php foreach ($galleryPhotosArr as $photo) {
        $photo = trim($photo);
        if ($photo !== '') {
          echo '<div class="w3-col s4 m3 l2" style="position: relative; display: inline-block; margin-right: 10px;">';
          echo '<img src="uploads/' . htmlspecialchars($photo) . '" style="width: 100%; max-height: 150px; object-fit: cover;" class="w3-card">';
          echo '<button type="button" class="w3-button w3-red w3-small" style="position: absolute; top: 2px; right: 2px;" onclick="removeExistingPhoto(this, \'' . htmlspecialchars($photo) . '\')">&times;</button>';
          echo '</div>';
        }
      } ?>
    </div>


    <!-- Description -->
    <label class="form-label mt-3">Property Description</label>
    <textarea class="w3-input w3-border w3-round" name="description" rows="5" placeholder="Describe the property..." required><?php echo $description; ?></textarea>

    <!-- Contact Info -->
    <label class="form-label mt-3">Contact Name</label>
    <input class="w3-input w3-border w3-round" type="text" name="contactName" placeholder="Your Name" value="<?php echo $contactName; ?>" required />

    <label class="form-label mt-3">Phone Number</label>
    <input class="w3-input w3-border w3-round" type="tel" name="phone" placeholder="Phone Number" value="<?php echo $phone; ?>" required />

    <label class="form-label mt-3">Email Address</label>
    <input class="w3-input w3-border w3-round" type="email" name="email" placeholder="Email Address" value="<?php echo $email; ?>" required />

    <!-- Publish -->
    <label class="form-label mt-3">Publish Status</label>
    <select class="w3-select w3-border w3-round" name="publish" required>
      <option value="1" <?php if ($publish === '1' || $publish === 1) echo "selected"; ?>>Published</option>
      <option value="0" <?php if ($publish === '0' || $publish === 0) echo "selected"; ?>>Unpublished</option>
    </select>

    <button type="submit" class="w3-button w3-blue w3-round w3-margin-top"><?php echo $id ? "Update" : "Submit"; ?> Listing</button>
  </form>
</section>

<script>
let photoId = 0;

function addPhotoInput() {
  const inputContainer = document.getElementById('photoInputs');
  const previewContainer = document.getElementById('galleryPreview');
  const id = 'photo-' + photoId++;

  // Create file input group
  const inputGroup = document.createElement('div');
  inputGroup.classList.add('w3-margin-bottom');
  inputGroup.setAttribute('data-id', id);

  const input = document.createElement('input');
  input.type = 'file';
  input.name = 'galleryPhotosNew[]';
  input.accept = 'image/*';
  input.classList.add('w3-input', 'w3-border', 'w3-round');

  input.onchange = function (e) {
    const file = e.target.files[0];
    if (file) {
      // Remove existing preview for this input if any
      const existing = previewContainer.querySelector(`[data-id="${id}"]`);
      if (existing) existing.remove();

      const imgWrapper = document.createElement('span');
      imgWrapper.classList.add('w3-margin-right');
      imgWrapper.style.position = 'relative';
      imgWrapper.setAttribute('data-id', id);

      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.classList.add('w3-card');
      img.style.maxHeight = '150px';
      img.style.marginRight = '10px';

      // Delete button over image
      const delBtn = document.createElement('button');
      delBtn.type = 'button';
      delBtn.innerHTML = '&times;';
      delBtn.classList.add('w3-button', 'w3-red', 'w3-small');
      delBtn.style.position = 'absolute';
      delBtn.style.top = '2px';
      delBtn.style.right = '2px';

      delBtn.onclick = () => {
        const confirmRemove = confirm("Are you sure you want to remove this new photo?");
        if (confirmRemove) {
          inputGroup.remove();
          imgWrapper.remove();
        }
      };

      imgWrapper.appendChild(img);
      imgWrapper.appendChild(delBtn);
      previewContainer.appendChild(imgWrapper);
    }
  };

  inputGroup.appendChild(input);
  inputContainer.appendChild(inputGroup);
}

function removeExistingPhoto(button, filename) {
  const confirmRemove = confirm("Are you sure you want to remove this existing photo?");
  if (!confirmRemove) return;

  const form = document.getElementById('propertyForm');

  // Add hidden input for the removed photo
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'removePhotos[]';
  input.value = filename;
  form.appendChild(input);

  // Remove photo preview
  button.parentElement.remove();
}

// Add a photo input by default when the page loads
window.onload = () => {
  addPhotoInput();
};
</script>
 </div><!--/.col (left) -->
            <!-- right column -->
        
          </div>   <!-- /.row -->
        </section><!-- /.content -->
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