<?php
  if( !defined("BASEPATH") ) { die(); }
?>
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
<!--- tpl:warning_notice:start -->
<div class="alert alert-warning">%content%</div>
<!--- tpl:warning_notice:end -->
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
%breadcrumbs%
%thread_notice%
<div style="overflow:auto;">
    <p class="pull-left" style="padding-top:15px;padding-bottom:0;margin:0;">
        %watch_link%
    </p>
    <p class="pull-right">
        %reply_button%
    </p>
</div>
<div class="panel panel-content" style="background-color:#fbfdff;border-bottom:1px solid #e1e1e1;">
    <div class="panel-body">
        <div class="row">

            <div class="col-md-2">
                <div class="panel panel-content">
                    <div class="panel-body post">
                        <span class="label label-danger starter">Starter</span>
                        <img src="%user_avatar%" class="img-thumbnail" style="min-width:100%;max-width:100%;">
                        <p>
                            <a href="%profile_url%" >%username%</a><br />
                            <small class="text-muted">Joined:</small> <small>%date_joined%</small><br />
                            <small class="text-muted">Messages:</small> <small>%postcount%</small>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-10">
                <div style="min-height:250px;">
                    %thread_content%
                </div>
                <div style="border-top:1px dashed #d3d3d3;margin:5px 0 5px 0;"></div>
                <div class="signature">
                    %user_signature%
                </div>
                <div class="tools">
                    <div class="pull-left">
                        <small>Posted %post_time%</small><br />
                        %mod_tools%
                    </div>
                    <div class="pull-right">
                        %quote_post%
                        %edit_post%
                        %report_post%
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!--- tpl:thread_starter:end -->
<!--- tpl:thread_top:start -->
%breadcrumbs%
%thread_notice%
<div style="overflow:auto;">
    <p class="pull-left" style="padding-top:15px;padding-bottom:0;margin:0;">
        %watch_link%
    </p>
    <p class="pull-right">
        %reply_button%
    </p>
</div>
<!--- tpl:thread_top:end -->
<!--- tpl:thread_reply:start -->
<div id="%post_id%">
<div class="panel panel-content" style="background-color:#fbfdff;border-bottom:1px solid #e1e1e1;">
    <div class="panel-body">
        <div class="row">

            <div class="col-md-2">
                <div class="panel panel-content">
                    <div class="panel-body post">
                        <img src="%user_avatar%" class="img-thumbnail" style="min-width:100%;max-width:100%;">
                        <p>
                            <a href="%profile_url%" >%username%</a><br />
                            <small class="text-muted">Joined:</small> <small>%date_joined%</small><br />
                            <small class="text-muted">Messages:</small> <small>%postcount%</small>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-10">
                <div style="min-height:250px;">
                    %reply_content%
                </div>
                <div style="border-top:1px dashed #d3d3d3;margin:5px 0 5px 0;"></div>
                <div class="signature">
                    %user_signature%
                </div>
                <div class="tools">
                    <div class="pull-left">
                        <small>Posted %post_time%</small><br />
                        %mod_tools%
                    </div>
                    <div class="pull-right">
                        %quote_post%
                        %edit_post%
                        %report_post%
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
<!--- tpl:thread_reply:end -->
<!--- tpl:reply_thread:start -->
<div id="reply" class="panel-body">
    <form id="%form_id%" action="%reply_form_action%" method="POST">
        %csrf_input%
        <textarea id="%editor_id%" style="width:100%;height:150px;max-width:100%;min-width:100%;" name="%textarea_name%"></textarea>
        <p class="pull-right" style="margin-top:5px;">
            <input type="submit" name="%submit_name%" value="Post Reply" />
        </p>
    </form>
</div>
<!--- tpl:reply_thread:end -->
<!--- tpl:forum_listings_category:start -->
<div class="panel panel-default">
    <div class="panel-heading"><b>%category_name%</b><br /><small>%category_desc%</small></div>
    <table class="table table-striped">
        <tbody>
            %category_forums%
        </tbody>
    </table>
</div>
<!--- tpl:forum_listings_category:end -->
<!--- tpl:forum_listings_node:start -->
<tr>
    @if( '%status%' == 'read') 
    <td><i class="fa fa-folder-o fa-3 node-read"></i></td>
    @else
    <td><i class="fa fa-folder fa-3 node-read"></i></td>
    @endif
    <td style="width:70%;">
        <span class="tooltip_toggle" data-toggle="tooltip" title="%node_desc%" data-placement="right">%node_name%</span><br />
        <small>Sub-Forums: %sub_forums%</small>
    </td>
    <td style="width:30%;">
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
    <td><img src="%user_avatar%" class="img-thumbnail pull-left" style="width:42;height:42px;margin-right:5px;" />%thread_name%<span class="label label-info pull-right">%status%</span><br /><small>%user%, %post_time%</small></td>
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
<div class="col-lg-9">
    <div class="well" style="overflow:auto;">
        <p class="pull-left">
            <span class="lead">%username% | %gender%</span><br />
            %usergroup%<br />
            <b>Joined:</b> %registered_date% <br />
            <b>Location:</b> %flag% %location%<br />
            <b>Age:</b> %age%

        </p>
        <p class="pull-right">
            <img src="%user_avatar%" class="img-thumbnail" style="width:75px;height:75px;" />
        </p>
    </div>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#profile_info" data-toggle="tab">Information</a></li>
        <li><a href="#profile_activity" data-toggle="tab">Recent Activity</a></li>
        <li><a href="#profile_comments" data-toggle="tab">Comments</a></li>
        <li><a href="#profile_visitors" data-toggle="tab">Visitors</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="profile_info">
            <strong>About User:</strong><br />
            <div class="well">
                %about_user%
            </div>
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
        <div class="tab-pane" id="profile_comments">
            <br />
            %comments%
        </div>
    </div>
</div>

<div class="col-lg-3">
    <div class="panel panel-content">
        <div class="panel-heading">
            <b>Visitors</b>
        </div>
        <div class="panel-body">
            %visitors%
        </div>
    </div>
</div>
<!--- tpl:user_profile_page:end -->
<!--- tpl:user_profile_comments:start -->
<div>
    <strong>WORK IN PROGRESS</strong><br />
    %writer% - %date%<br />
    %comment%
</div>
<!--- tpl:user_profile_comments:end -->
<!--- tpl:search_page:start -->
<ul class="nav nav-tabs">
    <li class="active"><a href="#search_threads" data-toggle="tab">Threads</a></li>
    <li><a href="#search_users" data-toggle="tab">Users</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="search_threads">
        <br />
        %searched_threads%
    </div>
    <div class="tab-pane" id="search_users">
        <br />
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
<div class="panel panel-content">
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
    <input type="text" name="%username_field_name%" value="%form_username_value%" id="username" />
    <label for="password">Password</label>
    <input type="password" name="%password_field_name%" id="password" />
    <label for="a_password">Confirm Password</label>
    <input type="password" name="%password_a_field_name%" id="a_password" />
    <label for="email">Email</label>
    <input type="text" name="%email_field_name%" value="%form_email_value%" id="email" />
    <label for="tangobb_captcha">Are you a bot?</label><br />
    %captcha%
    <br /><br />
    <input type="submit" name="%submit_name%" value="Register" />
    %register_notice%
</form>
<!--- tpl:register_form:end -->
<!--- tpl:login_form:start -->
<form action="" method="POST">
    <label for="email">Username or Email</label>
    <input type="text" name="%email_field_name%" id="email" />
    <label for="password">Password</label>
    <input type="password" name="%password_field_name%" id="password" />
    <br />
    <input type="submit" name="%submit_field_name%" value="Sign In" />
    <input type="checkbox" name="%remember_field_name%" /> Remember Me
    <br />
    <a href="%site_url%/members.php/cmd/forgotpassword">Forgot Password</a>
</form>
<!--- tpl:login_form:end -->
<!--- tpl:forget_password_form:start -->
<form action="" method="POST" id="tango_form">
    %csrf_field%
    <label for="email">Email</label>
    <input type="text" name="%email_field_name%" id="email" />
    <br /><br />
    <input type="submit" name="%submit_field_name%" value="Send Email" />
</form>
<!--- tpl:forget_password_form:end -->
<!--- tpl:reset_password_form:start -->
<form action="" method="POST" id="tango_form">
    %csrf_field%
    <label for="password">Password</label>
    <input type="password" name="%password_field_name%" id="password" />
    <label for="a_password">Confirm Password</label>
    <input type="password" name="%password_a_field_name%" id="a_password" />
    <br /><br />
    <input type="submit" name="%submit_field_name%" value="Reset Password" />
</form>
<!--- tpl:reset_password_form:end -->
<!--- tpl:conversation_read:start -->
<span class="pull-right"><i class="glyphicon glyphicon-eye-open"></i></span>
<!--- tpl:conversation_read:end -->
<!--- tpl:conversation_unread:start -->
<span class="pull-right"><i class="glyphicon glyphicon-eye-close"></i></span>
<!--- tpl:conversation_unread:end -->
<!--- tpl:conversation_new_message:start -->
<span class="label label-success pull-right">New messages</span>
<!--- tpl:conversation_new_message:end -->
<!--- tpl:conversation_delete:start -->
<a href="%link%"><i class="glyphicon glyphicon-trash"></i></a>
<!--- tpl:conversation_delete:end -->
<!--- tpl:conversation_overview:start -->
<div class="panel panel-default">
    <div class="panel-heading"><strong>%overview_header%</strong></div>
    %conversations%
</div>
<!--- tpl:conversation_overview:end -->
<!--- tpl:smiley_list:start -->
<br />
<ul class="nav nav-tabs">
    <li class="active"><a href="#smilies" data-toggle="tab">Smilies</a></li>
    <li><a href="#misc" data-toggle="tab">Misc</a></li>
    <li><a href="#food" data-toggle="tab">Food</a></li>
    <li><a href="#animals" data-toggle="tab">Animals</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="smilies">
        %smilies%
    </div>
    <div class="tab-pane" id="misc">
        %misc%
    </div>
    <div class="tab-pane" id="food">
        %food%
    </div>
    <div class="tab-pane" id="animals">
        %animals%
    </div>
</div>
<!--- tpl:smiliy_list:end -->
<!--- parent:theme_entity_file:end -->