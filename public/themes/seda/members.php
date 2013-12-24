<!DOCTYPE HTML>
<html>
	<head>
		<title>%page_title% - %site_name%</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		%editor_settings%
		<link href="%site_url%/public/themes/seda/assets/css/seda.css" rel="stylesheet">
	</head>
	<body>
	<div id="wrapper">
		
		@if ($TANGO->perm->check('access_moderation')) 
		
		<div id="staff_bar">
			<a href="%site_url%/mod" class="left reports"> Reports <span id="badge"> %mod_report_integer% </span> </a>
			@if ($TANGO->perm->check('access_administration')) 
			<a href="%site_url%/admin" target="_blank" class="right"> Admin Panel </a>
			@endif
		</div>
		
		@endif
		
		<section id="header">
			<div id="logo" class="left">
				<a href="%site_url%/forum.php">  <?php include('theme_settings.php'); ?> {{ $logo }} </a>
			</div>
			<form action="%site_url%/search.php" method="POST" class="right" role="search" id="search">
				<input type="text" name="search_query"  class="search-input" placeholder="Search Forum" >
				<input type="submit" name="search_submit" value="" class="search-button" />
			</form>
		</section>
		
<!-- NAVIGATION SECTION - START -->
		
		<section id="navigation">
			<span class="left">
				<ul>
					<li> <a href="%site_url%/forum.php">Forums</a> </li>
					<li> <a href="%site_url%/members.php" class="active">Members</a> </li>	
				</ul>
			</span>
			@if (!$TANGO->sess->isLogged) 
			<span class="right">
				<ul>
					<li> <a href="#" id="toggleLogin">Login</a> </li>
				</ul>
			</span>
			@else
			<span class="right">
				<ul class="normal">
					<li> <a href="#" id="dropdown"> %username% </a> </li>
					<ul class="dropdown animation fade">
						<li> <span class="space"> </span> </li>
                      @foreach ($TANGO->user->userLinks() as $name => $link)
                            <li>
                                <a href="{{ $link }}">
                                    {{ $name }}
                                </a>
                            </li>
                      @endforeach
					</ul> 
				</ul>
			</span>
		@endif
		</section>
		
<!-- NAVIGATION SECTION - END -->
    
<!-- CONTAINER SECTION - START -->
		
		<section id="main_container">
			<div id="sidebar" class="put_left">
				@if (!$TANGO->sess->isLogged) 
				<div id="box">
					<a href="%site_url%/members.php/cmd/register" class="register_button">Register</a>
				</div>
				@endif
				<div id="box">
					<div class="title"> Forum Statistics </div>
					<div class="content">
						Users: <span class="right">%bb_stat_users%</span> <br>
						Threads: <span class="right">%bb_stat_threads%</span> <br>
						Replies: <span class="right">%bb_stat_posts%</span> <br>
					</div>
				</div>
			</div>
			<div id="main_content">
				<div class="page_title">%page_title%</div>
				<div id="member_list">
					%content%
				</div>
			</div>
		</section>
		
<!-- CONTAINER SECTION - END -->

<!-- FOOTER SECTION - START -->
		
		<section id="footer">
			<span class="left">Powered by <a target="_blank" href="http://tangobb.net/">TangoBB</a></span>
			<span class="right">Seda Theme by <a target="_blank" href="http://www.seandavies.pw">Sean Davies </a>
		</section>
		
<!-- FOOTER SECTION - END -->
		
<!-- LOGIN BOX MODAL - START -->
		
      @if (!$TANGO->sess->isLogged) 
		<div id="loginBox" hidden="hidden">
			<div id="mask"></div>
			<form id="login_form" action="%site_url%/members.php/cmd/signin" method="POST">
				<div id="title"> Login </div>
				<div id="content">
					<input id="email" type="text" name="email" id="email" placeholder="Email">
					<input id="password" type="password" name="password" id="password" placeholder="Password"> 
					<label class="remember" for="remember">Remember Me?</label>
					<input id="remember" type="checkbox" name="remember">
					<input type="submit" name="signin" value="Login" id="login">
					<a href="" class="link"> Forgot Password? </a>
				</div>
			</form>
		</div>
		@endif
		
<!-- LOGIN BOX MODAL - END -->  
		</div>
		<script src="%site_url%/public/themes/seda/assets/js/seda.js"></script>
	</body>
</html>