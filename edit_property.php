<?php
include('header.php');
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

// Fetch categories for dropdown (same as before)
$query = "SELECT id, cate FROM category ORDER BY 1";
$result = mysqli_query($db, $query);

// Get property ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('Invalid property ID');
}

// Fetch property data
$sql = "SELECT * FROM property WHERE id = $id LIMIT 1";
$res = mysqli_query($db, $sql);
if (mysqli_num_rows($res) == 0) {
    die('Property not found');
}
$property = mysqli_fetch_assoc($res);

// Decode features (assuming stored as JSON array)
$features = json_decode($property['features'], true) ?: [];

?>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
<style>
#galleryPreview .col {
  position: relative;
}

#galleryPreview img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
  transition: transform 0.2s;
}

#galleryPreview img:hover {
  transform: scale(1.02);
}

</style>


<section class="w3-content w3-white w3-padding w3-card">
  <h2>Edit House Listing</h2>
  <form id="propertyForm" method="POST" enctype="multipart/form-data" action="update_property.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($property['id']) ?>" />

    <!-- Basic Details -->
    <div class="mb-4">
      <h4>Basic Property Details</h4>
      <div class="row g-3">
        <div class="col-md-6">
          <label for="title" class="form-label">Listing Title</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="e.g., Modern 4-Bedroom Duplex" required
            value="<?= htmlspecialchars($property['title']) ?>" />
        </div>
        <div class="col-md-3">
          <label for="type" class="form-label">Property Type</label>
          <select class="form-select form-control" id="type" name="type" required>
            <option disabled>Select</option>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <option <?= ($row['cate'] == $property['type']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['cate']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="listingType" class="form-label">Listing Type</label>
          <select class="form-select form-control" id="listingType" name="listingType" required>
            <option <?= ($property['listing_type'] == 'For Sale') ? 'selected' : '' ?>>For Sale</option>
            <option <?= ($property['listing_type'] == 'For Rent') ? 'selected' : '' ?>>For Rent</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="price" class="form-label">Price</label>
          <input type="number" class="form-control" id="price" name="price" required value="<?= htmlspecialchars($property['price']) ?>" />
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select class="form-select form-control" name="status" required>
            <option <?= ($property['status'] == 'Available') ? 'selected' : '' ?>>Available</option>
            <option <?= ($property['status'] == 'Sold') ? 'selected' : '' ?>>Sold</option>
            <option <?= ($property['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Location Details -->
    <div class="mb-4">
      <h4>Location Details</h4>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Country</label>
          <input type="text" class="form-control" name="country" required value="<?= htmlspecialchars($property['country']) ?>" />
        </div>
        <div class="col-md-4">
          <label class="form-label">State/Province</label>
          <input type="text" class="form-control" name="state" required value="<?= htmlspecialchars($property['state']) ?>" />
        </div>
        <div class="col-md-4">
          <label class="form-label">Location</label>
          <input type="text" class="form-control" name="city" required value="<?= htmlspecialchars($property['city']) ?>" />
        </div>
        <div class="col-md">
          <label class="form-label">Street Address</label>
          <input type="text" class="form-control" name="street" required value="<?= htmlspecialchars($property['street']) ?>" />
        </div>
      </div>
    </div>

    <!-- Specifications -->
    <div class="mb-4">
      <h4>Property Specifications</h4>
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label">Bedrooms</label>
          <input type="number" class="form-control" name="bedrooms" min="0" required value="<?= $property['bedrooms'] ?>" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Bathrooms</label>
          <input type="number" class="form-control" name="bathrooms" min="0" required value="<?= $property['bathrooms'] ?>" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Toilets</label>
          <input type="number" class="form-control" name="toilets" min="0" required value="<?= $property['toilets'] ?>" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Living Rooms</label>
          <input type="number" class="form-control" name="livingRooms" min="0" required value="<?= $property['living_rooms'] ?>" />
        </div>
        <div class="col-md-2">
          <label class="form-label">Kitchens</label>
          <input type="number" class="form-control" name="kitchens" min="0" required value="<?= $property['kitchens'] ?>" />
        </div>
    
        <div class="col-md-3">
          <label class="form-label">Property Size (sqm)</label>
          <input type="text" class="form-control" name="propertySize" value="<?= htmlspecialchars($property['property_size']) ?>" />
        </div>
        <div class="col-md-3">
          <label class="form-label">Land Size (optional)</label>
          <input type="text" class="form-control" name="landSize" value="<?= htmlspecialchars($property['land_size']) ?>" />
        </div>
      </div>
    </div>

    <!-- Features -->
    <div class="mb-4">
      <h4>Features & Amenities</h4>
      <div class="row">
        <?php
          $featureOptions = ["Parking Space", "Swimming Pool", "Internet Ready", "Air Conditioning"];
          foreach ($featureOptions as $feature):
            $checked = in_array($feature, $features) ? 'checked' : '';
        ?>
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="features[]" value="<?= $feature ?>" id="<?= strtolower(str_replace(' ', '', $feature)) ?>" <?= $checked ?> />
            <label class="form-check-label" for="<?= strtolower(str_replace(' ', '', $feature)) ?>"><?= $feature ?></label>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

 <!-- Gallery Photos -->
<div class="w3-margin-bottom">
  <h4>Gallery Photos</h4>

  <!-- Dynamic upload input area -->
  <div id="photoInputs" class="w3-container w3-padding-small w3-border w3-round w3-light-grey w3-margin-bottom"></div>
  <button type="button" class="w3-button w3-blue w3-small w3-margin-top" onclick="addPhotoInput()">➕ Add Photo</button>

  <!-- Gallery preview -->
  <div id="galleryPreview" class="w3-row-padding w3-margin-top">
    <?php
      $galleryImages = json_decode($property['gallery'], true) ?: [];
      foreach ($galleryImages as $index => $imgPath):
    ?>
      <div class="w3-third w3-container w3-margin-bottom" data-id="existing-<?= $index ?>">
        <div class="w3-display-container w3-hover-shadow">
          <img src="<?= htmlspecialchars($imgPath) ?>" class="w3-image" style="height: 200px; object-fit: cover; border-radius: 6px;">
          <button
            type="button"
            class="w3-button w3-red w3-tiny w3-display-topright"
            onclick="removeExistingImage(this, 'existing-<?= $index ?>')"
            style="padding: 2px 8px;"
          >
            &times;
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>


    <!-- Description -->
    <div class="mb-4">
      <h4>Property Description</h4>
      <textarea class="form-control" name="description" rows="5" placeholder="Describe the property, neighborhood, features, etc." required><?= htmlspecialchars($property['description']) ?></textarea>
    </div>

    <!-- Contact Info -->
    <div class="mb-4">
      <h4>Contact Information</h4>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Contact Name</label>
          <input type="text" class="form-control" name="contactName" required value="<?= htmlspecialchars($property['contact_name']) ?>" />
        </div>
        <div class="col-md-4">
          <label class="form-label">Phone Number</label>
          <input type="tel" class="form-control" name="phone" required value="<?= htmlspecialchars($property['phone']) ?>" />
        </div>
        <div class="col-md-4">
          <label class="form-label">Email Address</label>
          <input type="email" class="form-control" name="email" required value="<?= htmlspecialchars($property['email']) ?>" />
        </div>
      </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Update Listing</button>
  </form>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  let photoId = 0;
  const galleryPreview = document.getElementById('galleryPreview');
  const photoInputs = document.getElementById('photoInputs');

  // Store IDs of images marked for deletion
  const imagesToRemove = new Set();

  function addPhotoInput() {
    const id = 'photo-' + photoId++;

    const inputGroup = document.createElement('div');
    inputGroup.classList.add('d-flex', 'align-items-center', 'gap-2');
    inputGroup.setAttribute('data-id', id);

    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'galleryPhotos[]';
    input.accept = 'image/*';
    input.classList.add('form-control');

    input.onchange = function (e) {
      const file = e.target.files[0];
      if (file) {
        const existing = galleryPreview.querySelector(`[data-id="${id}"]`);
        if (existing) existing.remove();

        const imgWrapper = document.createElement('div');
        imgWrapper.classList.add('w3-third', 'w3-container', 'w3-margin-bottom');
        imgWrapper.setAttribute('data-id', id);

        const imgBox = document.createElement('div');
        imgBox.classList.add('w3-display-container', 'w3-hover-shadow');

        const img = document.createElement('img');
        img.classList.add('w3-image');
        img.style.height = '200px';
        img.style.objectFit = 'cover';
        img.style.borderRadius = '6px';

        const delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.innerHTML = '&times;';
        delBtn.classList.add('w3-button', 'w3-red', 'w3-tiny', 'w3-display-topright');
        delBtn.style.padding = '2px 8px';
        delBtn.onclick = () => {
          inputGroup.remove();
          imgWrapper.remove();
        };

        const reader = new FileReader();
        reader.onload = function (e) {
          img.src = e.target.result;
        };
        reader.readAsDataURL(file);

        imgBox.appendChild(img);
        imgBox.appendChild(delBtn);
        imgWrapper.appendChild(imgBox);
        galleryPreview.appendChild(imgWrapper);
      }
    };

    inputGroup.appendChild(input);
    photoInputs.appendChild(inputGroup);
  }

  // Remove existing image, mark for deletion
  function removeExistingImage(button, id) {
    const wrapper = button.parentElement;
    wrapper.remove();
    imagesToRemove.add(id);
  }

  // Before form submit, append hidden inputs with IDs of removed images
  $('#propertyForm').on('submit', function (e) {
    imagesToRemove.forEach(id => {
      $('<input>').attr({
        type: 'hidden',
        name: 'removeImages[]',
        value: id
      }).appendTo(this);
    });
  });

  // AJAX form submit with JSON response handling and redirect
  $(document).ready(function () {
    $('#propertyForm').on('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      $.ajax({
        url: 'update_property.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          try {
            const data = JSON.parse(response);
            if (data.status === 'success') {
              alert(data.message);
              window.location.href = data.redirect;
            } else {
              alert('Error: ' + data.message);
            }
          } catch (e) {
            alert('Unexpected response from server.');
          }
        },
        error: function (xhr, status, error) {
          alert('Something went wrong: ' + error);
        }
      });
    });

    // On load, add one photo input
    addPhotoInput();

    // Render existing images from hidden input JSON
    const existingGallery = JSON.parse(document.getElementById('existingGallery').value || '[]');
    renderExistingImages(existingGallery);
  });

  // Render existing images with delete buttons bound to removeExistingImage
  function renderExistingImages(images) {
    images.forEach((src, index) => {
      const imgWrapper = document.createElement('div');
      imgWrapper.classList.add('w3-third', 'w3-container', 'w3-margin-bottom');
      imgWrapper.setAttribute('data-id', `existing-${index}`);

      const imgBox = document.createElement('div');
      imgBox.classList.add('w3-display-container', 'w3-hover-shadow');

      const img = document.createElement('img');
      img.classList.add('w3-image');
      img.style.height = '200px';
      img.style.objectFit = 'cover';
      img.style.borderRadius = '6px';
      img.src = src;

      const delBtn = document.createElement('button');
      delBtn.type = 'button';
      delBtn.innerHTML = '&times;';
      delBtn.classList.add('w3-button', 'w3-red', 'w3-tiny', 'w3-display-topright');
      delBtn.style.padding = '2px 8px';
      delBtn.onclick = function () {
        removeExistingImage(this, `existing-${index}`);
      };

      imgBox.appendChild(img);
      imgBox.appendChild(delBtn);
      imgWrapper.appendChild(imgBox);
      galleryPreview.appendChild(imgWrapper);
    });
  }
</script>


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

       <script>
  document.addEventListener("DOMContentLoaded", function () {
    const typeSelect = document.getElementById("type");

    const featureIDs = ["parking", "pool", "internet", "ac"];
    const specNames = ["bedrooms", "bathrooms", "toilets", "livingRooms", "kitchens"];

    function toggleFields() {
      const selectedType = typeSelect.value?.toLowerCase() || "";
      const isLand = selectedType.includes("land");

      // Toggle Features
      featureIDs.forEach(id => {
        const feature = document.getElementById(id);
        const wrapper = feature?.closest(".col-md-3");
        if (wrapper) {
          wrapper.style.display = isLand ? "none" : "block";
          feature.required = !isLand;
        }
      });

      // Toggle Specifications
      specNames.forEach(name => {
        const input = document.querySelector(`[name="${name}"]`);
        const wrapper = input?.closest(".col-md-2");
        if (wrapper) {
          wrapper.style.display = isLand ? "none" : "block";
          input.required = !isLand;
        }
      });
    }

    // Initial run in case the form is reloaded with a selected value
    toggleFields();

    // Run on change of the property type dropdown
    typeSelect.addEventListener("change", toggleFields);
  });
</script>
  </body>
</html>