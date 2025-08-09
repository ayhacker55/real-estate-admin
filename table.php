<?php
// Connect and fetch properties from DB
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

function clean($data) {
    return htmlspecialchars(trim($data));
}

$query = "SELECT * FROM property ORDER BY id DESC";
$result = mysqli_query($db, $query);

$properties = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['gallery'] = json_decode($row['gallery'], true) ?: [];
    $properties[] = $row;
}
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Dashboard - Property Listings</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.1/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<style>
  body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .dashboard-header {
    padding: 1rem 2rem;
    background: #343a40;
    color: #fff;
    font-weight: 600;
    font-size: 1.5rem;
  }
  .container-dashboard {
    max-width: 1200px;
    margin: 2rem auto;
    background: white;
    padding: 1rem 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 0 20px rgb(0 0 0 / 0.1);
  }
  .table-responsive {
    max-height: 600px;
    overflow-y: auto;
  }
  thead th {
    position: sticky;
    top: 0;
    background-color: #343a40;
    color: white;
    z-index: 10;
    border-bottom: 2px solid #212529;
  }
  tbody tr:hover {
    background-color: #f1f3f5;
  }
  .thumb-img {
    width: 50px;
    height: 40px;
    object-fit: cover;
    border-radius: 5px;
    cursor: pointer;
    transition: transform 0.3s ease;
  }
  .thumb-img:hover {
    transform: scale(1.2);
  }
  .action-btn {
    border: none;
    background: transparent;
    cursor: pointer;
    margin-right: 6px;
    font-size: 1.25rem;
    color: #495057;
    transition: color 0.2s ease-in-out;
  }
  .action-btn:hover {
    color: #0d6efd;
  }
  .badge-available {
    background-color: #28a745 !important;
  }
  .badge-rented {
    background-color: #0d6efd !important;
  }
  .badge-sold {
    background-color: #dc3545 !important;
  }
  #searchInput {
    max-width: 350px;
    margin-bottom: 1rem;
  }
</style>
</head>
<body>

<header class="dashboard-header">
  Property Listings Admin Dashboard
</header>

<div class="container-dashboard">

  <input type="text" id="searchInput" class="form-control" placeholder="Search properties by title, city, or type...">

  <div class="table-responsive shadow rounded">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Type</th>
          <th>Listing</th>
          <th>Price</th>
          <th>Location</th>
          <th>Status</th>
          <th>Contact</th>
          <th>Gallery</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="propertyTableBody">
        <?php foreach ($properties as $property): 
          $status = strtolower($property['status']);
          $badgeClass = 'badge bg-secondary';
          if ($status === 'available') $badgeClass = 'badge badge-available';
          elseif ($status === 'rented' || $status === 'for rent') $badgeClass = 'badge badge-rented';
          elseif ($status === 'sold') $badgeClass = 'badge badge-sold';
        ?>
        <tr data-title="<?= strtolower(clean($property['title'])) ?>" data-city="<?= strtolower(clean($property['city'])) ?>" data-type="<?= strtolower(clean($property['type'])) ?>">
          <td><?= $property['id'] ?></td>
          <td><?= clean($property['title']) ?></td>
          <td><?= clean($property['type']) ?></td>
          <td><?= clean($property['listing_type']) ?></td>
          <td><?= clean($property['currency']) . ' ' . number_format($property['price'], 2) ?></td>
          <td><?= clean($property['city']) ?>, <?= clean($property['state']) ?>, <?= clean($property['country']) ?></td>
          <td><span class="<?= $badgeClass ?> text-white px-2 py-1 rounded"><?= clean($property['status']) ?></span></td>
          <td>
            <strong><?= clean($property['contact_name']) ?></strong><br>
            <small><i class="bi bi-telephone"></i> <?= clean($property['phone']) ?></small><br>
            <small><i class="bi bi-envelope"></i> <?= clean($property['email']) ?></small>
          </td>
          <td>
            <?php if (!empty($property['gallery'])): ?>
              <img src="<?= clean($property['gallery'][0]) ?>" alt="Thumbnail" class="thumb-img gallery-thumb" 
                   data-id="<?= $property['id'] ?>" data-title="<?= clean($property['title']) ?>"
                   data-gallery='<?= json_encode($property['gallery']) ?>' title="View Gallery" />
              <small class="text-muted ms-1">(<?= count($property['gallery']) ?>)</small>
            <?php else: ?>
              <span class="text-muted fst-italic">No images</span>
            <?php endif; ?>
          </td>
          <td>
            <button class="action-btn btn-gallery" title="View Gallery" 
                    data-id="<?= $property['id'] ?>" 
                    data-title="<?= clean($property['title']) ?>"
                    data-gallery='<?= json_encode($property['gallery']) ?>'>
              <i class="bi bi-card-image"></i>
            </button>
            <button class="action-btn btn-add-images text-success" title="Add Image" 
                    data-id="<?= $property['id'] ?>" 
                    data-title="<?= clean($property['title']) ?>">
              <i class="bi bi-plus-circle"></i>
            </button>
            <button class="action-btn btn-delete text-danger" title="Delete Property" 
                    data-id="<?= $property['id'] ?>" 
                    data-title="<?= clean($property['title']) ?>">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Reusable dynamic modal -->
<div class="modal fade" id="dynamicModal" tabindex="-1" aria-hidden="true" aria-labelledby="dynamicModalLabel">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="dynamicModalLabel">Modal Title</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="dynamicModalBody" style="max-height:60vh; overflow-y:auto;">
        <!-- Content gets injected here dynamically -->
      </div>
      <div class="modal-footer" id="dynamicModalFooter">
        <!-- Footer buttons dynamically injected -->
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
  document.querySelector('tbody#propertyTableBody').addEventListener('click', (e) => {
    const target = e.target.closest('button, img');
    if (!target) return;

    if (target.classList.contains('gallery-thumb') || target.classList.contains('btn-gallery')) {
      // Handle Gallery modal content
      const galleryData = target.dataset.gallery ? JSON.parse(target.dataset.gallery) : [];
      const title = target.dataset.title || 'Gallery';

      modalTitle.textContent = `Gallery for ${title}`;
      modalFooter.innerHTML = ''; // clear footer

      if (galleryData.length === 0) {
        modalBody.innerHTML = `<p class="text-muted text-center fst-italic mb-0">No images available.</p>`;
      } else {
        // Build carousel HTML dynamically
        let carouselInner = '';
        galleryData.forEach((imgUrl, i) => {
          carouselInner += `
            <div class="carousel-item ${i === 0 ? 'active' : ''}">
              <img src="${imgUrl}" class="d-block w-100" style="max-height:400px; object-fit:contain;" alt="Image ${i+1}">
            </div>`;
        });

        modalBody.innerHTML = `
          <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">${carouselInner}</div>
            <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>`;
      }
      dynamicModal.show();
    }
    // Handle Add Images modal
    else if (target.classList.contains('btn-add-images')) {
      const id = target.dataset.id;
      const title = target.dataset.title;

      modalTitle.textContent = `Add Images for ${title}`;
      modalFooter.innerHTML = ''; // clear footer

      modalBody.innerHTML = `
        <form id="addImagesForm" action="add_image.php" method="POST" enctype="multipart/form-data" novalidate>
          <input type="hidden" name="property_id" value="${id}">
          <div class="mb-3">
            <label for="imageFiles" class="form-label">Select Images (multiple allowed)</label>
            <input type="file" class="form-control" id="imageFiles" name="images[]" multiple accept="image/*" required>
          </div>
          <div id="addImagesMsg" class="mb-2"></div>
          <button type="submit" class="btn btn-success">Upload</button>
        </form>`;

      // Add form submission handler (AJAX style)
      const form = modalBody.querySelector('#addImagesForm');
      form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const msgBox = document.getElementById('addImagesMsg');
        msgBox.textContent = '';
        const formData = new FormData(form);
        try {
          const response = await fetch(form.action, {
            method: form.method,
            body: formData,
          });
          const text = await response.text();
          if(response.ok) {
            msgBox.className = 'alert alert-success';
            msgBox.textContent = 'Images uploaded successfully!';
            form.reset();
          } else {
            msgBox.className = 'alert alert-danger';
            msgBox.textContent = 'Error uploading images: ' + text;
          }
        } catch (err) {
          msgBox.className = 'alert alert-danger';
          msgBox.textContent = 'Network error: ' + err.message;
        }
      });

      dynamicModal.show();
    }
    // Handle Delete modal
    else if (target.classList.contains('btn-delete')) {
      const id = target.dataset.id;
      const title = target.dataset.title;

      modalTitle.textContent = `Delete Property "${title}"?`;
      modalBody.innerHTML = `<p>Are you sure you want to delete this property? This action cannot be undone.</p>`;

      modalFooter.innerHTML = `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form id="deleteForm" action="delete_property.php" method="POST" class="d-inline">
          <input type="hidden" name="property_id" value="${id}">
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>`;

      dynamicModal.show();
    }
  });

</script>

</body>
</html>
