<?php include('header.php')?>

<?php
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

// Total property count
$total_result = mysqli_query($db, "SELECT COUNT(*) as total FROM property");
$total_row = mysqli_fetch_assoc($total_result);
$total_properties = $total_row['total'];
$total_pages = ceil($total_properties / $limit);

// Fetch paginated property records
$query = "SELECT id, title, type, listing_type, price, status, country, state, city, street, gallery FROM property ORDER BY id DESC LIMIT $offset, $limit";
$result = mysqli_query($db, $query);
?>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Property Listings</h3>
        </div>

        <div class="box-body" style="overflow-x: auto;">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Photo</th>
                <th>Title</th>
                <th>Type</th>
                <th>Listing Type</th>
                <th>Price</th>
                <th>Status</th>
                <th>Country</th>
                <th>State</th>
                <th>City</th>
                <th>Street</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $serial = $offset + 1;
              while ($row = mysqli_fetch_assoc($result)) {
                // Get first photo
                $firstPhoto = '';
                if (!empty($row['gallery'])) {
                  $images = explode(',', $row['gallery']);
                  $firstPhoto = trim($images[0]);
                }

                echo "<tr>
                        <td>{$serial}</td>
                        <td><img src='uploads/{$firstPhoto}' alt='Photo' style='width: 100px; height: 70px; object-fit: cover;'></td>
                        <td>{$row['title']}</td>
                        <td>{$row['type']}</td>
                        <td>{$row['listing_type']}</td>
                        <td>\${$row['price']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['country']}</td>
                        <td>{$row['state']}</td>
                        <td>{$row['city']}</td>
                        <td>{$row['street']}</td>
                        <td>
                          <a href='edit_property.php?id={$row['id']}' class='btn btn-xs btn-primary'>Edit</a>
                          <a href='delete_property.php?id={$row['id']}' class='btn btn-xs btn-danger' onclick='return confirm(\"Delete this property?\")'>Delete</a>
                          <a href='approve_property.php?id={$row['id']}' class='btn btn-xs btn-success'>Approve</a>
                        </td>
                      </tr>";
                $serial++;
              }
              ?>
            </tbody>
          </table>
        </div>

        <div class="box-footer clearfix">
          <ul class="pagination pagination-sm no-margin pull-right">
            <?php if ($page > 1): ?>
              <li><a href="?page=<?php echo $page - 1; ?>">&laquo;</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
              <li><a href="?page=<?php echo $page + 1; ?>">&raquo;</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
