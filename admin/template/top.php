<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TangoBB Administration</title>

    <!-- Bootstrap -->
    <link href="<?php echo SITE_URL; ?>/public/css/admin.css" rel="stylesheet">
    <link href="<?php echo SITE_URL; ?>/public/codemirror/lib/codemirror.css" rel="stylesheet" media="screen">
    <link href="<?php echo SITE_URL; ?>/public/codemirror/addon/display/fullscreen.css" rel="stylesheet" media="screen">
    <link href="<?php echo SITE_URL; ?>/public/codemirror/addon/scroll/simplescrollbars.css" rel="stylesheet" media="screen">
    <link href="<?php echo SITE_URL; ?>/public/codemirror/theme/neo.css" rel="stylesheet" media="screen">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

      <div class="row">
          <div class="col-md-2 sidebar">
              <div class="container">
                  <div class="logo"></div>
                  <ul class="nav nav-pills nav-stacked">
                      <li class="active"><a href="javascript:return false;">Welcome,  <?php echo $TANGO->sess->data['username']; ?></a></li>
                      <li><a href="<?php echo SITE_URL; ?>/admin"><i class="glyphicon glyphicon-dashboard"></i> Dashboard</a></li>
                      <li><a href="<?php echo SITE_URL; ?>/admin/terminal.php"><i class="fa fa-terminal"></i> Terminal</a></li>
                      <?php echo $ADMIN->navigation(); ?>
                  </ul>

                  <div class="sidebar-links">
                      <a href="<?php echo SITE_URL; ?>" class="btn btn-success btn-lg" data-toggle="tooltip"
                   title="Back To Forum">
                    <i class="glyphicon glyphicon-log-out"></i>
                </a>
                <a href="<?php echo SITE_URL; ?>/members.php/cmd/logout" class="btn btn-success btn-lg"
                   data-toggle="tooltip" title="Log Out">
                    <i class="glyphicon glyphicon-off"></i>
                </a><br />
                      Powered by TangoBB

                  </div>
              </div>
          </div>

          <div class="col-md-10 main-box">
              <div class="row main-content">
