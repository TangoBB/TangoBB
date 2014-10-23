<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>%site_name%</title>

    <!-- Bootstrap -->
    <link href="%site_url%/public/themes/pointfour/assets/css/codetana.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    %editor_settings%

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <div class="top">

      <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div class="collapse navbar-collapse" id="main-nav">
            <ul class="nav navbar-nav">
              <li><a href="https://github.com/Codetana/blogdown">blogdown</a></li>
              <li><a href="http://iko.im/">Iko</a></li>
              <li><a href="https://github.com/Codetana/IkarusDB">IkarusDB</a></li>
              <li><a href="http://ffs.im">FFS.Im</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              @if($TANGO->sess->isLogged)
              <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i> %username%</a>
              <ul class="dropdown-menu">
                @foreach ($TANGO->user->userLinks() as $name => $link)
                <li>
                  <a href="{{ $link }}">
                    {{ $name }}
                  </a>
                </li>
                @endforeach
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-envelope"></i> Conversations
                @if (count($TANGO->user->userMessages()) > 0)
                <span class="label label-success">{{ count($TANGO->user->userMessages()) }}</span>
                @endif
              </a>
              <ul class="dropdown-menu">
                @if (count($TANGO->user->userMessages()) > 0)
                @foreach ($TANGO->user->userMessages() as $msg)
                <li><a href="{{ $msg['view_url'] }}">
                  <h4>
                    {{ $msg['message_title'] }}
                    </h4>
                    <small>By {{ $msg['message_sender'] }} at
                      {{ date('F j, Y', $msg['message_time']) }}</small>
                  </a>
                </li>
                @endforeach
                @else
                <li role="presentation" class="disabled"><a role="menuitem" tabindex="-1" href="#">No Messages</a></li>
                @endif
                <li><a href="%site_url%/conversations.php">Conversations</a></li>
                <li><a href="%site_url%/conversations.php/cmd/new">New Message</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="overflow:auto;">
                <i class="fa fa-bell"></i>
                @if (count($TANGO->user->notifications()) > 0)
                <span class="badge">
                  {{ count($TANGO->user->notifications()) }}
                </span>
                @endif
              </a>
              <ul class="dropdown-menu">
                @if (count($TANGO->user->notifications()) < 1)
                <li role="presentation" class="disabled"><a role="menuitem" tabindex="-1" href="#">No new notifications yet.</a></li>
                @else
                 @foreach ($TANGO->user->notifications() as $note)
                 <li><a href="{{ $note['notice_link'] }}">
                   {{ $note['notice_content'] }}
                   <small class="text-muted">{{ date('F j, Y', $note['time_received']) }}</small>
                   </a>
                 </li>
                 @endforeach
                 {{ $TANGO->user->clearNotification() }}
                @endif
              </ul>
            </li>
          </ul>
              @else
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Log In</a>
                <ul class="dropdown-menu" role="menu">
                  <li class="login-form">
                <form action="%site_url%/members.php/cmd/signin" method="POST">
                  <input type="text" name="email" class="form-control" id="inputEmail3" placeholder="Username or Email">
                  <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password">
                  <label>
                    <input type="checkbox" name="remember"> Remember me
                  </label>
                  <input type="submit" class="btn btn-primary btn-block" name="signin" value="Log In" />
                                    @if ($TANGO->data['facebook_authenticate'] == "1")
                  <a href="%facebook_login_url%" class="btn btn-info btn-block"><span class="label label-primary"><i class="fa fa-facebook"></i></span> Sign In</a>
                  @endif
                  </form>
              </li>
              @endif
                </ul>
              </div>
            </div>
          </nav>

    </div>

    <div class="container">

      <div class="well well-sm">
        <ul class="nav nav-pills">
          <li class="active"><a href="%site_url%">Forum</a></li>
          <li><a href="%site_url%/members.php">Members</a></li>
        </ul>
      </div>

      <h1>%site_name%</h1>

      <div class="row">

        <div class="col-md-9">
          %forum_listings%

          <div class="well">
            <strong>Users Online</strong><br />
            %users_online%
          </div>

          <div class="row forum-legends">
            <div class="col-md-6">
              <i class="fa fa-folder fa-3 node-read"></i> <span class="text-muted legend-desc">New Posts</span>
            </div>
            <div class="col-md-6">
              <i class="fa fa-folder-o fa-3 node-read"></i> <span class="text-muted legend-desc">No New Posts</span>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <form action="http://localhost/Iko/search.php" method="POST" class="search">
            <input name="search_query" type="text" class="form-control tooltip_toggle" placeholder="Search...">
            <input type="submit" name="search_submit" style="visibility:hidden;display:none;" value="Search" />
          </form>
          <div class="panel panel-default">
            <div class="panel-body">
              @if ($TANGO->sess->isLogged)
              <div class="row" style="width:100%;overflow:auto;margin:0 auto;">
                <div class="col-md-5">
                  <img src="%user_avatar%" class="img-thumbnail pull-left" style="max-width:100x;max-height:100px;">
                </div>
                <div class="col-md-7">
                  <a href="%site_url%/members.php/cmd/user">%username_style%</a>
                  <div class="row">
                    <div class="col-md-6">
                      <small>Messages:</small>
                    </div>
                    <div class="col-md-6" style="text-align:right;">
                      %user_post_count%
                    </div>
                  </div>
                </div>
              </div>
              @else
              <a href="%site_url%/members.php/cmd/register" class="btn btn-success btn-block btn-lg">Register</a>
              @endif
            </div>
          </div>
          <div class="panel panel-warning">
            <div class="panel-heading"><b><i class="glyphicon glyphicon-stats"></i> Forum Statistics</b></div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6 text-muted"><small>Threads:</small></div>
                <div class="col-md-6" style="text-align:right;"><small>%bb_stat_threads%</small></div>
                <div class="col-md-6 text-muted"><small>Replies:</small></div>
                <div class="col-md-6" style="text-align:right;"><small>%bb_stat_posts%</small></div>
                <div class="col-md-6 text-muted"><small>Users:</small></div>
                <div class="col-md-6" style="text-align:right;"><small>%bb_stat_users%</small></div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <footer class="footer text-muted">
        &copy; Codetana 2014 - Founded by Jian Ting<br />
        <a href="http://iko.im/">Powered by Iko</a><br />
        <a href="https://www.digitalocean.com/?refcode=a7452fdefaea"><div class="hosted_by"></div></a>
      </footer>
    </div>

    %highlighter_footer%
    <script src="%site_url%/public/themes/pointfour/assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="%site_url%/public/themes/pointfour/assets/js/theme.js" type="text/javascript"></script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-54803535-1', 'auto');
      ga('send', 'pageview');
    </script>

  </body>
</html>