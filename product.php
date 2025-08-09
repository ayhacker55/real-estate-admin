<?php include('header.php')?>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
<style type="text/css">#galleryPreview img {
<style>
  #galleryPreview img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 0.375rem;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    transition: transform 0.2s;
  }

  #galleryPreview img:hover {
    transform: scale(1.05);
  }
</style>

<?php
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

// Fetch paginated categories
$query = "SELECT id, cate FROM category ORDER BY 1";
$result = mysqli_query($db, $query);
?>
        <!-- Main content -->
        <section class="w3-content w3-white w3-padding w3-card">
         
             
 <h2 class="mb-4">Upload House for Sale</h2>
  <form id="propertyForm" method="POST" enctype="multipart/form-data">

    <!-- Basic Details -->
    <div class="mb-4">
      <h4>Basic Property Details</h4>
      <div class="row g-3">
        <div class="col-md-6">
          <label for="title" class="form-label">Listing Title</label>
          <input type="text" class="form-control " id="title" name="title" placeholder="e.g., Modern 4-Bedroom Duplex" required />
        </div>
        <div class="col-md-3">
          <label for="type" class="form-label">Property Type</label>
          <select class="form-select form-control " id="type" name="type" required>
            <option selected disabled>Select</option>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
          echo "
                 
                   <option>{$row['cate']}</option>";
          $serial++;
        }
        ?>
           
         
          </select>
        </div>
        <div class="col-md-3">
          <label for="listingType" class="form-label">Listing Type</label>
          <select class="form-select form-control " id="listingType" name="listingType" required>
            <option>For Sale</option>
            <option>For Rent</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="price" class="form-label">Price</label>
          <input type="number" class="form-control " id="price" name="price" required />
        </div>
       
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select class="form-select form-control " name="status" required>
            <option>Available</option>
            <option>Sold</option>
            <option>Pending</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Location -->
    <div class="mb-4">
      <h4>Location Details</h4>
      <div class="row g-3">
      <div class="col-md-4">
  <label class="form-label">State/Province</label>
  <select class="form-select form-control" name="state" required>
    <option selected disabled>Select State</option>
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
</div>

        <div class="col-md-4">
          <label class="form-label">Location</label>
          <input type="text" class="form-control " name="city" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">Street Address</label>
          <input type="text" class="form-control " name="street" required />
        </div>
        
      </div>
    </div>

    <!-- Specifications -->
    <div class="mb-4">
      <h4>Property Specifications</h4>
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label">Bedrooms</label>
          <input type="number" class="form-control " name="bedrooms" min="0" required />
        </div>
        <div class="col-md-2">
          <label class="form-label">Bathrooms</label>
          <input type="number" class="form-control " name="bathrooms" min="0" required />
        </div>
        <div class="col-md-2">
          <label class="form-label">Toilets</label>
          <input type="number" class="form-control " name="toilets" min="0" required />
        </div>
        <div class="col-md-2">
          <label class="form-label">Living Rooms</label>
          <input type="number" class="form-control " name="livingRooms" min="0" required />
        </div>
        <div class="col-md-2">
          <label class="form-label">Kitchens</label>
          <input type="number" class="form-control " name="kitchens" min="0" required />
        </div>
        
        <div class="col-md-3">
          <label class="form-label">Property Size (sqm)</label>
          <input type="text" class="form-control " name="propertySize" />
        </div>
        <div class="col-md-3">
          <label class="form-label">Land Size (optional)</label>
          <input type="text" class="form-control " name="landSize" />
        </div>
      </div>
    </div>

    <!-- Features -->
    <div class="mb-4">
      <h4>Features & Amenities</h4>
      <div class="row">
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="features[]" value="Parking Space" id="parking" />
            <label class="form-check-label" for="parking">Parking Space</label>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="features[]" value="Swimming Pool" id="pool" />
            <label class="form-check-label" for="pool">Swimming Pool</label>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="features[]" value="Internet Ready" id="internet" />
            <label class="form-check-label" for="internet">Internet Ready</label>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="features[]" value="Air Conditioning" id="ac" />
            <label class="form-check-label" for="ac">Air Conditioning</label>
          </div>
        </div>
      </div>
    </div>

    <!-- Gallery Photos -->
    <div class="mb-4">
      <h4>Gallery Photos</h4>
      <div id="photoInputs" class="d-flex flex-column gap-3"></div>
      <button type="button" class="btn btn-outline-primary mt-2" onclick="addPhotoInput()">➕ Add Photo</button>
    </div>
<span id="galleryPreview" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3"></span>





    <!-- Description -->
    <div class="mb-4">
      <h4>Property Description</h4>
      <textarea class="form-control " name="description" rows="5" placeholder="Describe the property, neighborhood, features, etc." required></textarea>
    </div>

    <!-- Contact Info -->
    <div class="mb-4">
      <h4>Contact Information</h4>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Contact Name</label>
          <input type="text" class="form-control " name="contactName" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">Phone Number</label>
          <input type="tel" class="form-control " name="phone" required />
        </div>
        <div class="col-md-4">
          <label class="form-label">Email Address</label>
          <input type="email" class="form-control " name="email" required />
        </div>
      </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary">Submit Listing</button>
  </form>

     

         

      

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  let photoId = 0;

  function addPhotoInput() {
    const inputContainer = document.getElementById('photoInputs');
    const previewContainer = document.getElementById('galleryPreview');

    const id = 'photo-' + photoId++;

    // Create file input group
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('d-flex', 'align-items-center', 'gap-2');
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
        url: 'upload.php',
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