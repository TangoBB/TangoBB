<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>%page_title% | %site_name%</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="%site_url%/public/themes/WithMe/assets/css/bootstrap.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="%site_url%/public/themes/WithMe/assets/css/flat-ui.css" rel="stylesheet">
    <link href="%site_url%/public/themes/WithMe/assets/css/withme.css" rel="stylesheet">

    <link rel="shortcut icon" href="images/favicon.ico">
    %editor_settings%

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
  </head>
  <body>

    <div class="container">

      <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main_nav">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="%site_url%" class="navbar-brand" href="#">%site_name%</a>
        </div>
        <div class="collapse navbar-collapse" id="main_nav">
            <ul class="nav navbar-nav">
                <li><a href="%site_url%/forum.php">Forum</a></li>
                <li class="active"><a href="%site_url%/members.php">Members</a></li>
            </ul>
            <form action="%site_url%/search.php" method="POST" class="navbar-form navbar-right" role="search">
                <div class="form-group">
                    <input type="text" name="search_query" class="form-control" placeholder="Search">
                </div>
                <input type="submit" name="search_submit" value="" class="search-button" style="display:none;visibility:none;" />
            </form>
        </div>
      </nav>

      <div class="wrap">

        <div class="header">
            <h1 class="pull-left">%page_title%</h1>
            <div class="pull-right">
                @if ($TANGO->sess->isLogged)
                <div class="btn-group user_links">
                    <button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope"></i>
                        @if (count($TANGO->user->userMessages()) > 0)
                        <span class="label label-danger">{{ count($TANGO->user->userMessages()) }}</span>
                        @endif
                    </button>
                    <span class="dropdown-arrow dropdown-arrow-inverse"></span>
                    <ul class="dropdown-menu dropdown-inverse" role="menu" style="width:200px;">
                        @if (count($TANGO->user->userMessages()) > 0)
                        @foreach ($TANGO->user->userMessages() as $msg)
                        <li><a href="{{ $msg['view_url'] }}">
                            <strong>{{ $msg['message_title'] }}</strong>
                                <br />
                                <small>By {{ $msg['message_sender'] }} at
                                    {{ date('F j, Y', $msg['message_time']) }}</small>
                            </a>
                        </li>
                        @endforeach
                        @else
                        <li role="presentation" class="dropdown-header">No Messages</li>
                        @endif
                        <li><a href="%site_url%/conversations.php/cmd/new">New Message</a></li>
                    </ul>
                </div>
                <div class="btn-group user_links">
                    <button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                    </button>
                    <span class="dropdown-arrow dropdown-arrow-inverse"></span>
                    <ul class="dropdown-menu dropdown-inverse">
                        @foreach ($TANGO->user->userLinks() as $name => $link)
                        <li>
                            <a href="{{ $link }}">
                                {{ $name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <div class="user_links">
                    <a class="btn btn-primary btn-lg" data-toggle="modal" data-target="#loginModal">
                        Sign In
                    </a>
                </div>
                @endif

            </div>
        </div>

        <div class="row">

            <div class="col-md-9">
                %content%
            </div>

            <div class="col-md-3">
                @if ($TANGO->sess->isLogged)
                <div class="panel panel-default">
                  <div class="panel-body">
                      <div class="row">
                        <div class="col-md-12">
                          <a href="%site_url%/members.php/cmd/user/id/{{ $TANGO->sess->data['id']; }}">%username_style%</a>
                        </div>
                        <div class="col-md-6 text-muted"><small>Messages:</small></div>
                        <div class="col-md-6" style="text-align:right;"><small>%user_post_count%</small></div>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="glyphicon glyphicon-user"></i> Users Online</h3>
                    </div>
                    <div class="panel-body">
                        %users_online%
                    </div>
                </div>

            </div>

        </div>

        <div class="footer">
            <a href="%site_url%/members.php/cmd/rules">Rules</a> | <a href="%site_url%/members.php">Members</a> |
            @if ($TANGO->sess->isLogged)
            <a href="%site_url%/members.php/cmd/logout">Log Out</a>
            @else
            <a href="%site_url%/members.php/cmd/register">Sign Up</a>
            @endif
            <br />
            Powered by <a href="#">TangoBB</a>
        </div>

      </div>

    </div>
    <!-- /.container -->

    @if (!$TANGO->sess->isLogged)
    <div class="modal fade" id="loginModal">
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


    <!-- Load JS here for greater good =============================-->
    <script src="%site_url%/public/themes/WithMe/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="%site_url%/public/themes/WithMe/assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="%site_url%/public/themes/WithMe/assets/js/bootstrap.min.js"></script>
    <script src="%site_url%/public/themes/WithMe/assets/js/bootstrap-select.js"></script>
    <script src="%site_url%/public/themes/WithMe/assets/js/bootstrap-switch.js"></script>
    <script src="%site_url%/public/themes/WithMe/assets/js/flatui-checkbox.js"></script>
    <script src="%site_url%/public/themes/WithMe/assets/js/flatui-radio.js"></script>
    <script src="%site_url%/public/themes/WithMe/assets/js/jquery.tagsinput.js"></script>
    <script src="%site_url%/public/themes/WithMe/assets/js/jquery.placeholder.js"></script>
    <script src="%site_url%/public/themes/WithMe/assets/js/withme.js"></script>
  </body>
</html>
