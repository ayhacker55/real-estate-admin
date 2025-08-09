<?php
session_start();
include('db.php');

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['username'];

// Fetch username and profile photo from DB
$stmt = $db->prepare("SELECT username, profile_photo FROM admins WHERE username = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $profile_photo);
$stmt->fetch();
$stmt->close();

// Default profile photo if none set
if (empty($profile_photo)) {
    $profile_photo = "dist/img/default-profile.png";
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Page | Dashboard</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <!-- CSS and scripts as before -->
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
  <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" />
  <link href="dist/css/AdminLTE.min.css" rel="stylesheet" />
  <link href="dist/css/skins/_all-skins.min.css" rel="stylesheet" />
  <!-- add your other CSS/JS includes -->
</head>
<body class="skin-blue">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="dashboard.php" class="logo">
      <span class="logo-mini"><b>A</b>LT</span>
      <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>

    <!-- Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button -->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- Messages, Notifications, Tasks menus here (same as before, omitted for brevity) -->

          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- Use dynamic profile photo -->
              <img src="uploads/admins/<?php echo htmlspecialchars($profile_photo); ?>" class="user-image" alt="User Image"/>
              <span class="hidden-xs"><?php echo htmlspecialchars($username); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image in dropdown -->
              <li class="user-header">
                <img src="<?php echo htmlspecialchars($profile_photo); ?>" class="img-circle" alt="User Image" />
                <p>
                  <?php echo htmlspecialchars($username); ?> - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li>
              <!-- User body menu (optional links) -->
              <li class="user-body">
                <div class="col-xs-4 text-center">
                  <a href="#">Followers</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="#">Sales</a>
                </div>
                <div class="col-xs-4 text-center">
                  <a href="#">Friends</a>
                </div>
              </li>
              <!-- Footer -->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>

      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
             <img src="uploads/admins/<?php echo htmlspecialchars($profile_photo); ?>" class="user-image" alt="User Image"/>
            </div>
            <div class="pull-left info">
              <p><?php echo htmlspecialchars($username); ?></p>

              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
      
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="active treeview">
              <a href="#">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
            </li>
           <li class="treeview">
              <a href="#">
                <i class="fa fa-user"></i>
                <span>Admin</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <li><a href="edit_profile.php"><i class="fa fa-user"></i>My profile</a></li>
            <li><a href="admin.php"><i class="fa fa-user"></i>Create New Admin</a></li>
              <li><a href="vadmin.php"><i class="fa fa-eye"></i>View Admin</a></li>
                 <li><a href="password.php"><i class="fa fa-key"></i>Change Password</a></li>
              </ul>
            </li>

                     
              <li class="treeview">
              <a href="#">
                <i class="fa fa-home"></i>
                <span>Real Estate</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="product.php"><i class="fa fa-upload"></i>Upload Property</a></li>
                <li><a href="vproduct.php"><i class="fa fa-eye"></i>View Property</a></li>
                      <li><a href="cate1.php"><i class="fa fa-upload"></i>Add Category</a></li>
                <li><a href="vcate1.php"><i class="fa fa-eye"></i>View Category</a></li>
               
              </ul>
            </li>

              <li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i>
                <span>Building Materials</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="building.php"><i class="fa fa-upload"></i>Upload Product</a></li>
                   <li><a href="vbuilding.php"><i class="fa fa-eye"></i>View Product</a></li>
                <li><a href="cate2.php"><i class="fa fa-upload"></i>Add Category</a></li>
                <li><a href="vcate2.php"><i class="fa fa-eye"></i>View Category</a></li>
               
              </ul>
            </li>

              <li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i>
                <span>Decor</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
             
              <ul class="treeview-menu">
                 <li><a href="decor.php"><i class="fa fa-upload"></i>Upload Product</a></li>
                  <li><a href="vdecoration.php"><i class="fa fa-eye"></i>View Product</a></li>
            <li><a href="cate3.php"><i class="fa fa-upload"></i>Add Category</a></li>
                <li><a href="vcate3.php"><i class="fa fa-eye"></i>View Category</a></li>
               
               
              </ul>
            </li>

   <li class="treeview">
              <a href="#">
                 <i class="ion ion-document-text"></i>
                <span>Blog</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
             
              <ul class="treeview-menu">
                 <li><a href="blog.php"><i class="fa fa-upload"></i>Upload Blog</a></li>
                  <li><a href="vblog.php"><i class="fa fa-eye"></i>View Blogs</a></li>
              </ul>
            </li>

                <li class="treeview">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Agent</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                  <li><a href="documentation/dashboard.php"><i class="fa fa-eye"></i>View Admin</a></li>
                   <li><a href="documentation/dashboard.php"><i class="fa fa-upload"></i>Agents Post</a></li>
               
              </ul>
            </li>

        
     
         
     
            <li>
              <a href="pages/mailbox/mailbox.php">
                <i class="fa fa-envelope"></i> <span>Mailbox</span>
                <small class="label pull-right bg-yellow">12</small>
              </a>
            </li>
          
          
            <li><a href="documentation/dashboard.php"><i class="fa fa-sign-out"></i>Logout</a></li>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

       <!-- Right side column. Contains the navbar and content of the page -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>