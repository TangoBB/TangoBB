<?php
  global $ADMIN, $TANGO;
?>
<!DOCTYPE html>
<html>
  <head>
    <title>TangoBB Administration Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="<?php echo SITE_URL; ?>/public/css/old-acp-bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo SITE_URL; ?>/public/css/flat-ui.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo SITE_URL; ?>/public/css/old-admin.css" rel="stylesheet" media="screen">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
  </head>
  <body>

      <div class="container">

          <div class="row">
              <div class="col-md-2">
                  <div class="logo"></div>
              </div>
          </div>

          <div class="row">

              <div class="col-md-2">
                  <ul class="nav nav-pills nav-stacked">
                      <li class="active"><a href="javascript:return false;">Welcome,  <?php echo $TANGO->sess->data['username']; ?></a></li>
                      <li><a href="<?php echo SITE_URL; ?>/admin"><i class="glyphicon glyphicon-dashboard"></i> Dashboard</a></li>
                      <li><a href="<?php echo SITE_URL; ?>/admin/terminal.php"><i class="fa fa-terminal"></i> Terminal</a></li>
                      <?php echo $ADMIN->navigation(); ?>
                  </ul>
                  <div align="center" class="options" id="tooltip">
                    <a href="<?php echo SITE_URL; ?>" class="btn btn-default btn-lg" data-toggle="tooltip" title="Back To Forum">
                      <i class="glyphicon glyphicon-log-out"></i>
                    </a>
                    <a href="<?php echo SITE_URL; ?>/members.php/cmd/logout" class="btn btn-default btn-lg" data-toggle="tooltip" title="Log Out">
                      <i class="glyphicon glyphicon-off"></i>
                    </a>
                  </div>

              </div>

              <div class="col-md-10">

                  <div class="row">
