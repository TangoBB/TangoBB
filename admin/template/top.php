<!DOCTYPE html>
<html>
  <head>
    <title>TangoBB Administrators' Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?php echo SITE_URL; ?>/public/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo SITE_URL; ?>/public/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo SITE_URL; ?>/public/css/admin.css" rel="stylesheet" media="screen">
  </head>
  <body>
    
      <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
          <div class="container">
              
              <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#">TangoBB</a>
              </div>
              
              <div class="collapse navbar-collapse">
                  <ul class="nav navbar-nav">
                      <li><a href="<?php echo SITE_URL; ?>">View Forum</a></li>
                  </ul>
              </div>
              
          </div>
      </nav>
      
      <div class="container">
          
          <div class="row">
              
              <div class="col-md-2">
                  
                  <ul class="nav nav-pills nav-stacked">
                      <li class="active"><a href="javascript:return false;">Welcome, jtPox</a></li>
                      <li><a href="<?php echo SITE_URL; ?>/admin">Dashboard</a></li>
                      <li><a href="<?php echo SITE_URL; ?>/admin/terminal.php">Terminal</a></li>
                      <?php echo $ADMIN->navigation(); ?>
                  </ul>
                  
              </div>
              
              <div class="col-md-10">
                  
                  <div class="row">