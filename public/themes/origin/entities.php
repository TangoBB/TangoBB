<!--- parent:theme_entity_file:start -->
<!--- tpl:quote_post:start -->
<blockquote>%quoted_post_content%<small>%quoted_post_user%</small></blockquote>
<!--- tpl:quote_post:end -->
<!--- tpl:thread_closed:start -->
<i class="glyphicon glyphicon-lock pull-right" title="Thread Closed"></i>
<!--- tpl:thread_closed:end -->
<!--- tpl:thread_stickied:start -->
<i class="glyphicon glyphicon-tag pull-right" style="margin-right:5px;" title="Thread Stickied"></i>
<!--- tpl:thread_stickied:end -->
<!--- tpl:danger_notice:start -->
<div class="alert alert-danger">%content%</div>
<!--- tpl:danger_notice:end -->
<!--- tpl:success_notice:start -->
<div class="alert alert-success">%content%</div>
<!--- tpl:success_notice:end -->
<!--- tpl:breadcrumbs:start -->
<ol class="breadcrumb">
    %bread%
</ol>
<!--- tpl:breadcrumbs:end -->
<!--- tpl:breadcrumbs_before:start -->
<li><a href="%link%">%name%</a></li>
<!--- tpl:breadcrumbs_before:end -->
<!--- tpl:breadcrumbs_current:start -->
<li class="active">%name%</li>
<!--- tpl:breadcrumbs_current:end -->
<!--- tpl:pagination:start -->
<ul class="pagination pull-right">
    %pages%
</ul>
<!--- tpl:pagination:end -->
<!--- tpl:pagination_links:start -->
<li><a href="%url%">%page%</a></li>
<!--- tpl:pagination_links:end -->
<!--- tpl:pagination_link_current:start -->
<li class="active"><a href="#">%page%</a></li>
<!--- tpl:pagination_link_current:end -->
<!--- tpl:members_page_head:start -->
<div style="height:auto;">
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <i class="glyphicon glyphicon-sort"></i>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="%sort_date_joined_asc%"><i class="glyphicon glyphicon-sort-by-order"></i> Date Joined Ascending</a></li>
            <li><a href="%sort_date_joined_desc%"><i class="glyphicon glyphicon-sort-by-order-alt"></i> Date Joined Descending</a></li>
            <li><a href="%sort_name_asc%"><i class="glyphicon glyphicon-sort-by-alphabet"></i> Username Ascending</a></li>
            <li><a href="%sort_name_desc%"><i class="glyphicon glyphicon-sort-by-alphabet-alt"></i> Username Descending</a></li>
        </ul>
    </div>
</div>
%members%
<!--- tpl:members_page_head:end -->
<!--- tpl:members_page:start -->
<div style="padding:10px 0px 10px 0px;border-bottom:dashed 1px #ccc;overflow:auto;">
    <img src="%avatar%" style="width:65px;height:65px;margin-right:5px;" class="img-thumbnail pull-left" />
    <a href="%profile_url%" >%username%</a><br />
    <small class="text-muted">Joined:</small> <small>%date_joined%</small><br />
    <small class="text-muted">Messages:</small> <small>%postcount%</small>
</div>
<!--- tpl:members_page:end -->
<!--- tpl:thread_starter:start -->
<div style="height:auto;overflow:auto;">
    <p class="pull-left">
        %breadcrumbs%
    </p>
    <p class="pull-right">
        %reply_button%
    </p>
</div>
<div id="starter" class="panel panel-default">
    <div class="panel-body">
        <div style="border-bottom:1px dotted #ccc;overflow:auto;height:auto;">
            <p class="pull-left">
                <img src="%user_avatar%" style="width:65px;height:65px;margin-right:5px;" class="img-thumbnail pull-left" />
                <span class="pull-left">
                    <a href="%profile_url%" >%username%</a> <span class="text-danger">(Starter)</span><br />
                    <small class="text-muted">Joined:</small> <small>%date_joined%</small><br />
                    <small class="text-muted">Messages:</small> <small>%postcount%</small>
                </span>
            </p>
            <p class="pull-right" id="thread_tools">
                %quote_post%
                %edit_post%
                %report_post%
            </p>
        </div>
        <div style="padding:20px 0 20px 0">
            %thread_content%
        </div>
        <div style="border-top:1px dotted #ccc;padding-top:5px;max-height:350px;">
            %user_signature%
        </div>
        <div style="overflow:auto;" class="text-muted">
            <p class="pull-left">
                <small>
                    Posted %post_time%
                </small>
            </p>
            <p class="pull-right">
                <small>
                    %mod_tools%
                </small>
            </p>
        </div>
    </div>
</div>
<!--- tpl:thread_starter:end -->
<!--- tpl:thread_reply:start -->
<div id="%post_id%">
<div id="thread_reply" class="panel panel-default">
    <div class="panel-body">
        <div style="border-bottom:1px dotted #ccc;overflow:auto;height:auto;">
            <p class="pull-left">
                <img src="%user_avatar%" style="width:65px;height:65px;margin-right:5px;" class="img-thumbnail pull-left" />
                <span class="pull-left">
                    <a href="%profile_url%" >%username%</a><br />
                    <small class="text-muted">Joined:</small> <small>%date_joined%</small><br />
                    <small class="text-muted">Messages:</small> <small>%postcount%</small><br />
                </span>
            </p>
            <p class="pull-right" id="post_tools">
                %quote_post%
                %edit_post%
                %report_post%
            </p>
        </div>
        <div style="padding:20px 0 20px 0">
            %reply_content%
        </div>
        <div style="border-top:1px dotted #ccc;padding-top:5px;max-height:350px;">
            %user_signature%
        </div>
        <div style="overflow:auto;" class="text-muted">
            <p class="pull-left">
                <small>
                    Posted %post_time%
                </small>
            </p>
            <p class="pull-right">
                <small>
                    %mod_tools%
                </small>
            </p>
        </div>
    </div>
</div>
</div>
<!--- tpl:thread_reply:end -->
<!--- tpl:reply_thread:start -->
<div id="reply" style="overflow:auto;" class="panel panel-default">
    <div class="panel-body">
        <form id="%form_id%" action="%reply_form_action%" method="POST">
            %csrf_input%
            <textarea id="%editor_id%" style="width:100%;height:150px;max-width:100%;min-width:100%;" name="%textarea_name%"></textarea>
            <p class="pull-right" style="margin-top:5px;">
                <input type="submit" name="%submit_name%" value="Post Reply" />
            </p>
        </form>
    </div>
</div>
<!--- tpl:reply_thread:end -->
<!--- tpl:forum_listings_category:start -->
<div class="panel panel-default">
    <div class="panel-heading"><b>%category_name%</b><br /><small>%category_desc%</small></div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th style="width:70%;"></th>
                <th style="width:30%;"></th>
            </tr>
        </thead>
        <tbody>
            %category_forums%
        </tbody>
    </table>
</div>
<!--- tpl:forum_listings_category:end -->
<!--- tpl:forum_listings_node:start -->
<tr>
    <td>
        %node_name%<br />
        <small>%node_desc%</small><br />
        <small>Sub-Forums: %sub_forums%</small>
    </td>
    <td>
        %latest_post%
    </td>
</tr>
<!--- tpl:forum_listings_node:end -->
<!--- tpl:forum_listings_node_latest:start -->
<img src="%user_avatar%" class="img-thumbnail pull-left" style="width:42;height:42px;margin-right:5px;" />
%latest_post%<br /><small>%post_user%, %post_time%</small>
<!--- tpl:forum_listings_node_latest:end -->
<!--- tpl:forum_listings_node_threads:start -->
%breadcrumbs%
%sub_forums%
<div style="height:auto;">
    <p class="pull-right">
        %post_thread_button%
    </p>
    <p class="pull-left">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-sort"></i>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="%sort_latest_created%"><i class="glyphicon glyphicon-sort-by-order"></i> By Latest Created</a></li>
                <li><a href="%sort_name_desc%"><i class="glyphicon glyphicon-sort-by-alphabet-alt"></i> By Name Descending</a></li>
                <li><a href="%sort_name_asc%"><i class="glyphicon glyphicon-sort-by-alphabet"></i> By Name Ascending</a></li>
                <li><a href="%sort_last_updated%"><i class="glyphicon glyphicon-sort-by-attributes-alt"></i> By Last Updated</a></li>
            </ul>
        </div>
    </p>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th style="width:70%;">Thread</th>
            <th style="width:30%;">Last Post</th>
        </tr>
    </thead>
    <tbody>
        %threads%
    </tbody>
</table>
<!--- tpl:forum_listings_node_threads:end -->
<!--- tpl:forum_listings_node_sub_forums:start -->
<table class="table table-hover">
    <thead>
        <tr>
            <th style="width:70%;"></th>
            <th style="width:30%;"></th>
        </tr>
    </thead>
    <tbody>
        %nodes%
    </tbody>
</table>
<!--- tpl:forum_listings_node_sub_forums:end -->
<!--- tpl:forum_listings_node_sub_forums_posts:start -->
<tr>
    <td>%node_name%<br /><small>%node_desc%</small></td>
    <td>
        %latest_post%
    </td>
</tr>
<!--- tpl:forum_listings_node_sub_forums_posts:end -->
<!--- tpl:forum_listings_node_sub_forums_latest:start -->
<img src="%user_avatar%" class="img-thumbnail pull-left" style="width:42;height:42px;margin-right:5px;" />
<span class="pull-right" style="text-align:right;">
    %latest_post%<br />
    <small>%post_user%, %post_time%</small>
</span>
<!--- tpl:forum_listings_node_sub_forums_latest:end -->
<!--- tpl:forum_listings_node_threads_posts:start -->
<tr>
    <td><img src="%user_avatar%" class="img-thumbnail pull-left" style="width:42;height:42px;margin-right:5px;" />%thread_name%<br /><small>%user%, %post_time%</small></td>
    <td>
        %latest_post%
    </td>
</tr>
<!--- tpl:forum_listings_node_threads_posts:end -->
<!--- tpl:forum_listings_node_threads_latestreply:start -->
<img src="%user_avatar%" class="img-thumbnail pull-left" style="width:42;height:42px;margin-right:5px;" />
<span class="pull-right" style="text-align:right;">
    %post_user%<br /><small>%post_time%</small>
</span>
<!--- tpl:forum_listings_node_threads_latestreply:end -->
<!--- tpl:create_thread:start -->
%breadcrumbs%
<form id="%form_id%" action="%create_thread_form_action%" method="POST">
    %csrf_input%
    <input type="text" name="%title_name%" placeholder="Thread Title..." class="form-control" />
    <br />
    <textarea id="%editor_id%" style="width:100%;height:300px;max-width:100%;" name="%textarea_name%"></textarea>
    <div class="center-block" style="margin-top:5px;">
        <input type="submit" name="%submit_name%" value="Create Thread" />
    </div>
</form>
<!--- tpl:create_thread:end -->
<!--- tpl:reply_thread_page:start -->
%breadcrumbs%
%quote_post%
<form id="%form_id%" action="%create_thread_form_action%" method="POST">
    %csrf_input%
    <textarea id="%editor_id%" style="width:100%;height:300px;max-width:100%;" name="%textarea_name%"></textarea>
    <div class="center-block" style="margin-top:5px;">
        <input type="submit" name="%submit_name%" value="Reply Thread" />
        <a href="%thread_url%">Return</a>
    </div>
</form>
<!--- tpl:reply_thread_page:end -->
<!--- tpl:user_profile_page:start -->
<div class="well" style="overflow:auto;">
    <p class="pull-left">
        <font class="lead">%username%</font>
        <br />
        (%usergroup%)<br />
        <b>Registered On:</b> %registered_date%
    </p>
    <p class="pull-right">
        <img src="%user_avatar%" class="img-thumbnail" style="width:75px;height:75px;" />
    </p>
</div>
<ul class="nav nav-tabs">
    <li class="active"><a href="#profile_info" data-toggle="tab">Information</a></li>
    <li><a href="#profile_activity" data-toggle="tab">Recent Activity</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="profile_info">
        <strong>Signature:</strong><br />
        <div class="well">
            %user_signature%
        </div>
        %mod_tools%
    </div>
    <div class="tab-pane" id="profile_activity">
        <br />
        %recent_activity%
    </div>
</div>
<!--- tpl:user_profile_page:end -->
<!--- tpl:search_page:start -->
<ul class="nav nav-tabs">
    <li class="active"><a href="#search_threads" data-toggle="tab">Threads</a></li>
    <li><a href="#search_users" data-toggle="tab">Users</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="search_threads">
        %searched_threads%
    </div>
    <div class="tab-pane" id="search_users">
        %searched_users%
    </div>
</div>
<!--- tpl:search_page:end -->
<!--- tpl:mod_reports:start -->
<ul class="nav nav-tabs">
    <li class="active"><a href="#mod_post_reports" data-toggle="tab">Reported Posts</a></li>
    <li><a href="#mod_user_reports" data-toggle="tab">Reported Users</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="mod_post_reports">
        <br /><br />
        %reported_posts%
    </div>
    <div class="tab-pane" id="mod_user_reports">
        <br /><br />
        %reported_users%
    </div>
</div>
<!--- tpl:mod_reports:end -->
<!--- tpl:content_box:start -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">%content_header%</h3>
    </div>
    <div class="panel-body">
        %content_body%
    </div>
</div>
<!--- tpl:content_box:end -->
<!--- tpl:register_form:start -->
<form action="" method="POST">
    %notice%
    %csrf_field%
    <label for="username">Username</label>
    <input type="text" name="%username_field_name%" id="username" />
    <label for="password">Password</label>
    <input type="password" name="%password_field_name%" id="password" />
    <label for="a_password">Confirm Password</label>
    <input type="password" name="%password_a_field_name%" id="a_password" />
    <label for="email">Email</label>
    <input type="text" name="%email_field_name%" id="email" />
    <br /><br />
    <input type="submit" name="%submit_name%" value="Register" />
    %register_notice%
</form>
<!--- tpl:register_form:end -->
<!--- tpl:login_form:start -->
<form action="" method="POST">
    <label for="email">Email</label>
    <input type="text" name="%email_field_name%" id="email" />
    <label for="password">Password</label>
    <input type="password" name="%password_field_name%" id="password" />
    <br />
    <input type="submit" name="%submit_field_name%" value="Sign In" />
    <input type="checkbox" name="%remember_field_name%" /> Remember Me
    <br />
    <a href="%site_url%/members.php/cmd/forgetpassword">Forgot Password</a>
</form>
<!--- tpl:login_form:end -->
<!--- tpl:forget_password_form:start -->
<form action="" method="POST" id="tango_form">
    %csrf_field%
    <label for="email">Email</label>
    <input type="text" name="%email_field_name%" id="email" />
    <br /><br />
    <input type="submit" name="%submit_field_name%" value="Reset Password" />
</form>
<!--- tpl:forget_password_form:end -->
<!--- parent:theme_entity_file:end -->