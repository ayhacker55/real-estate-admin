<?php include('header.php');

$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

// Total records
$total_result = mysqli_query($db, "SELECT COUNT(*) as total FROM blog_posts");
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);

// Fetch blog posts
$query = "SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT $offset, $limit";
$result = mysqli_query($db, $query);
?>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header"><h3 class="box-title">Blog Posts</h3></div>

        <div class="box-body table-responsive" style="max-height: 600px; overflow-y: auto;">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>#</th>
                <th>Media</th>
                <th>Title</th>
                <th>Author</th>
                <th>Email</th>
                <th>Content</th>
                <th>Type</th>
                <th>Created At</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $serial = $offset + 1; ?>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?= $serial++ ?></td>
                  <td>
                    <?php if ($row['media_type'] == 'image'): ?>
                      <img src="uploads/<?= htmlspecialchars($row['media_path']) ?>" style="width:100px; height:70px; object-fit:cover;">
                    <?php elseif ($row['media_type'] == 'youtube'): ?>
                      <a href="<?= htmlspecialchars($row['media_path']) ?>" target="_blank">YouTube Link</a>
                    <?php else: ?>
                      <span class="text-muted">No media</span>
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($row['title']) ?></td>
                  <td><?= htmlspecialchars($row['author']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= nl2br(htmlspecialchars(substr($row['content'], 0, 100))) ?>...</td>
                  <td><?= htmlspecialchars($row['media_type']) ?></td>
                  <td><?= date("Y-m-d", strtotime($row['created_at'])) ?></td>
                  <td>
                    <?php
                      $status = $row['status'] ?? 'pending';
                      if ($status == 'approved') {
                          echo '<span class="label label-success">Published</span>';
                      } elseif ($status == 'rejected') {
                          echo '<span class="label label-danger">Rejected</span>';
                      } else {
                          echo '<span class="label label-warning">Pending</span>';
                      }
                    ?>
                  </td>
                  <td>
                    <div class="dropdown">
                      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                        Actions <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li><a href="edit_blog.php?id=<?= $row['id'] ?>"><i class="fa fa-pencil"></i> Edit</a></li>
                        <li><a href="delete_blog.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this blog post?')"><i class="fa fa-trash text-danger"></i> Delete</a></li>
                        <li><a href="approve_blog.php?id=<?= $row['id'] ?>"><i class="fa fa-check text-success"></i> Approve</a></li>
                        <li><a href="reject_blog.php?id=<?= $row['id'] ?>"><i class="fa fa-times text-danger"></i> Reject</a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="box-footer clearfix">
          <ul class="pagination pagination-sm no-margin pull-right">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="<?= ($i == $page) ? 'active' : '' ?>">
                <a href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </div>

      </div>
    </div>
  </div>
</section>

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