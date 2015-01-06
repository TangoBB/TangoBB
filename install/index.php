<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TangoBB Installation</title>

    <!-- Bootstrap -->
    <link href="assets/css/install.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery.min.js"></script>

    <script src="assets/js/install.js"></script>
    <script>
      $(function() {
        var load_msg = '<div class="panel-body"><div align="center"><img src="assets/img/load.gif" alt="Loading..." /></div></div>';
        $('#main_content').html(load_msg).load('pages/server_check.php');
      });
    </script>
  </head>
  <body>

    <nav class="navbar navbar-default" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#install-top">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">TangoBB installation</a>
        </div>
        <div class="collapse navbar-collapse" id="install-top">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="http://codetana.com/">Codetana</a></li>
            <li><a href="https://github.com/Codetana/Iko/wiki">Documentation</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      <div class="row">
        <div class="col-md-6 col-md-offset-3">

          <div class="panel panel-primary" id="main_content">
          </div>

        </div>
      </div>
      <footer class="footer text-muted">
        TangoBB by Codetana
      </footer>
    </div>

    <script src="assets/js/bootstrap.min.js"></script>

  </body>
</html>
