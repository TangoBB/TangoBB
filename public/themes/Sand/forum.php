<?php
  if( !defined("BASEPATH") ) { die(); }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>%site_name%</title>

    <!-- Bootstrap -->
    <link href="%site_url%/public/themes/Sand/assets/css/sand.css" rel="stylesheet">
    %editor_settings%
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    @if ($TANGO->sess->isLogged)
    <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#user-nav">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse" id="user-nav">
          <ul class="nav navbar-nav">
            @if ($TANGO->perm->check('access_moderation'))
            <li><a href="%site_url%/mod">Reports <span class="label label-warning">%mod_report_integer%</span></a></li>
            @endif
            @if ($TANGO->perm->check('access_administration'))
            <li><a href="%site_url%/admin">Admin CP</a></li>
            @endif
          </ul>
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
        </div>
      </div>
    </nav>
    @endif
    
    <div class="container">

      <div class="row">
        <div class="col-md-10">
          <h1><div class="logo"></div></h1>
        </div>
        <div class="col-md-2">
          @if( !$TANGO->sess->isLogged )
          <div class="btn-group login-bar">
            <a href="#" class="btn btn-warning btn-block dropdown-toggle" data-toggle="dropdown">Log In</a>
            <ul class="dropdown-menu dropdown-menu-right" role="menu">
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
          </div>
          @endif
        </div>
      </div>
      <div class="well well-sm">
        <ul class="nav nav-pills">
          <li class="active"><a href="%site_url%">Forum</a></li>
          <li><a href="%site_url%/members.php">Members</a></li>
        </ul>
      </div>

      <div class="row">

        <div class="col-md-9">
          <h1 class="title">%site_name%</h1>
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
          <form action="%site_url%/search.php" method="POST" class="search">
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
              <a href="%site_url%/members.php/cmd/register" class="btn btn-success btn-block">Register</a>
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

      <footer class="footer">
        <div class="row">
          <div class="col-md-6">
            <a href="http://tangobb.com/">Powered by TangoBB</a><br />
            <small class="text-muted">Load Time: %elapsed_time% seconds | Memory: %memory_usage%</small>
          </div>
          <div class="col-md-6 change_theme">
            @if( $TANGO->sess->isLogged )
            <div class="btn-group dropup">
              <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                Change Theme <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu">
                @foreach (listThemes() as $theme)
                @if ($theme['theme_name'] == $TANGO->sess->data['chosen_theme'])
                <li class="active"><a href="{{ $theme['change_link'] }}">
                  {{ $theme['theme_name'] }}
                </a></li>
                @else
                <li><a href="{{ $theme['change_link'] }}">
                  {{ $theme['theme_name'] }}
                </a></li>
                @endif
                @endforeach
              </ul>
            </div>
            @endif
          </div>
        </div>
      </footer>

    </div>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    %highlighter_footer%
    <script src="%site_url%/public/themes/Sand/assets/js/bootstrap.min.js"></script>
    <script src="%site_url%/public/themes/Sand/assets/js/sand.js"></script>
  </body>
</html>