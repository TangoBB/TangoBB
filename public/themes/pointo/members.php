<!DOCTYPE html>
<html>
  <head>
    <title>%page_title% | %site_name%</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="%site_url%/public/themes/pointo/assets/css/bootstrap.css" rel="stylesheet">
    <link href="%site_url%/public/themes/pointo/assets/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="%site_url%/public/themes/pointo/assets/css/tangobb.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
    %editor_settings%
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
    @if ($TANGO->perm->check('access_moderation'))
    <nav class="navbar navbar-inverse" role="navigation" style="margin-bottom:0px;">
        <div class="container">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="%site_url%/mod">Reports <span class="label label-primary">%mod_report_integer%</span></a></li>
                    @if ($TANGO->perm->check('access_administration'))
                    <li><a href="%site_url%/admin">Admin CP</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @endif
      
    <div id="top">
        <div class="container">
            <a href="%site_url%"><div class="logo pull-left"></div></a>
            <div class="pull-right" style="width:20%;">
                <form action="%site_url%/search.php" method="POST" class="right" role="search" id="search">
                    <div class="input-group search">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="text" name="search_query" class="form-control" placeholder="Search" >
                    </div>
                    <input type="submit" name="search_submit" value="" class="search-button" style="display:none;visibility:none;" />
                </form>
            </div>
        </div>
    </div>
      
    <nav class="navbar navbar-default" role="navigation" style="margin-bottom:0;">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        
        <div class="container">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="%site_url%/forum.php">Forum</a></li>
                <li class="active"><a href="%site_url%/members.php">Members</a></li>
                <li><a href="http://library.tangobb.net/">Downloads</a></li>
                <li><a href="https://github.com/TangoBB/TangoBB/wiki">Documentation</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              @if ($TANGO->sess->isLogged)
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
                  <i class="glyphicon glyphicon-envelope"></i> Messages
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
                              <br />
                              <small>By {{ $msg['message_sender'] }} at
                                  {{ date('F j, Y', $msg['message_time']) }}</small>
                          </h4>
                          </a>
                      </li>
                      @endforeach
                      
                      @else
                      <li role="presentation" class="dropdown-header">No Messages</li>
                      @endif
                      <li><a href="%site_url%/conversations.php/cmd/new">New Message</a></li>
                </ul>
              </li>
              @else
              <li><a href="#" data-toggle="modal" data-target="#loginModal">Sign In</a></li>
              @endif
            </ul>
        </div>
    </nav>
        
    <div id="wrapper" class="container">
        
        <div class="row">
            <div class="col-md-9">
                <h1 style="margin-top:-5px;">%page_title%</h1>
                %content%
            </div>
            <div class="col-md-3">
                @if ($TANGO->sess->isLogged)
                <div class="panel panel-default">
                  <div class="panel-body">
                    <img src="%user_avatar%" class="img-thumbnail pull-left" style="width:100x;height:100px;margin-right:10px;">
                    <div class="pull-left">
                      <div class="row">
                        <div class="col-md-12">
                          <a href="%site_url%/members.php/cmd/user/id/{{ $TANGO->sess->data['id']; }}">%username_style%</a>
                        </div>
                        <div class="col-md-6 text-muted"><small>Messages:</small></div>
                        <div class="col-md-6" style="text-align:right;"><small>%user_post_count%</small></div>
                      </div>
                    </div>
                  </div>
                </div>
                @else
                <div class="panel panel-default">
                  <div class="panel-body">
                    <a href="%site_url%/members.php/cmd/register" class="btn btn-info btn-lg btn-block">Register</a>
                  </div>
                </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="glyphicon glyphicon-stats"></i> Forum Statistics</h3>
                    </div>
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
      </div>
    </div>
        
    <div id="footer" class="container">
        <span class="pull-left">
          Powered by <a href="http://tangobb.net/" id="fl">TangoBB</a>
        </span>
        <div class="btn-group dropup pull-right">
          <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" title="Theme Changer">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
            @foreach (listThemes() as $theme)
            @if ($theme['theme_name'] == $TANGO->sess->data['chosen_theme'])
            <li class="active">
              <a href="{{ $theme['change_link'] }}">
                {{ $theme['theme_name'] }}
              </a>
            </li>
            @else
            <li>
              <a href="{{ $theme['change_link'] }}">
                {{ $theme['theme_name'] }}
              </a>
            </li>
            @endif
            @endforeach
          </ul>
        </div>
    </div>
        
    @if (!$TANGO->sess->isLogged)
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title">Sign In</h4>
                  </div>
                  <div class="modal-body">
                      <form action="%site_url%/members.php/cmd/signin" method="POST">
                          <label for="email">Email</label>
                          <input type="text" name="email" id="email" class="form-control" required />
                          <label for="password">Password</label>
                          <input type="password" name="password" id="email" class="form-control" required />
                          <p class="pull-left">
                              <label class="remember" for="remember">Remember Me?</label>
                              <input id="remember" type="checkbox" name="remember">
                          </p>
                          <p class="pull-right">
                              <a href="%site_url%/members.php/cmd/forgetpassword" class="link"> Forgot Password? </a>
                          </p>
                          <input type="submit" name="signin" value="Sign In" class="btn btn-default btn-block" />
                      </form>
                  </div>
              </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endif

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="%site_url%/public/themes/pointo/assets/js/bootstrap.min.js"></script>
  </body>
</html>