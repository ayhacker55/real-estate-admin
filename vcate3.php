<?php include('header.php')?>
 
<?php
$db = @mysqli_connect("localhost", "root", "", "real_estate") or die("Could not connect");

// Pagination setup
$limit = 10; // Categories per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure it's at least 1
$offset = ($page - 1) * $limit;

// Get total categories count
$total_result = mysqli_query($db, "SELECT COUNT(*) as total FROM category3");
$total_row = mysqli_fetch_assoc($total_result);
$total_categories = $total_row['total'];
$total_pages = ceil($total_categories / $limit);

// Fetch paginated categories
$query = "SELECT id, cate FROM category3 ORDER BY id DESC LIMIT $offset, $limit";
$result = mysqli_query($db, $query);
?>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <div class="col-md-10">
         <div class="box">
  <div class="box-header">
    <h3 class="box-title">Real Estate Category</h3>
  </div>

  <div class="box-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Category</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $serial = $offset + 1;
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>
                  <td>{$serial}.</td>
                  <td>{$row['cate']}</td>
                  <td>
                    <a href='delete_category3.php?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>
                      <i class='fa fa-trash-o' style='color:red'></i>
                    </a>
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