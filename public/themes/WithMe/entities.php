<!--- parent:theme_entity_file:start -->
<!--- tpl:quote_post:start -->
<blockquote>%quoted_post_content%<small>~ %quoted_post_user%</small></blockquote>
<!--- tpl:quote_post:end -->
<!--- tpl:thread_closed:start -->
<i class="glyphicon glyphicon-lock pull-right" title="Thread Closed"></i>
<!--- tpl:thread_closed:end -->
<!--- tpl:thread_stickied:start -->
<i class="glyphicon glyphicon-pushpin pull-right" style="margin-right:5px;" title="Thread Pinned"></i>
<!--- tpl:thread_stickied:end -->
<!--- tpl:danger_notice:start -->
<div class="alert alert-danger">%content%</div>
<!--- tpl:danger_notice:end -->
<!--- tpl:success_notice:start -->
<div class="alert alert-success">%content%</div>
<!--- tpl:success_notice:end -->
<!--- tpl:breadcrumbs:start -->
<ol class="breadcrumb pull-left">
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
<!--- tpl:members_page:start -->
<div class="media" style="border-bottom:1px solid #e1e1e1;">
    <a class="pull-left" href="#">
        <img class="media-object" src="%avatar%" style="width:85px;height:85px;">
    </a>
    <div class="media-body">
        <a href="%profile_url%" >%username%</a><br />
        <small class="text-muted">Joined:</small> <small>%date_joined%</small><br />
        <small class="text-muted">Messages:</small> <small>%postcount%</small>
    </div>
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
<div class="panel panel-default post">
    <div class="panel-body">
        <div class="row" style="width:100%;">
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <img src="%user_avatar%" class="img-thumbnail" style="width:100%;height:120px;" />
                        <p align="center">
                            <a href="%profile_url%" >%username%</a>
                        </p>
                        Date Joined:<br />
                        <small class="text-muted pull-right">
                            %date_joined%
                        </small><br />
                        Posts:<br />
                        <small class="text-muted pull-right">
                            %postcount%
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                %thread_content%
                <div class="post_tools">
                    %quote_post%
                    %edit_post%
                    %report_post%
                </div>
                <div style="border-bottom:1px dashed #e1e1e1;"></div>
                <div class="signature">
                    %user_signature%
                </div>
                <small class="text-muted">Posted %post_time%</small>
                <div class="mod_tools">
                    %mod_tools%
                </div>
            </div>
        </div>
    </div>
</div>
<!--- tpl:thread_starter:end -->
<!--- tpl:thread_reply:start -->
<div id="%post_id%">
<div class="panel panel-default post">
    <div class="panel-body">
        <div class="row" style="width:100%;">
            <div class="col-md-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <img src="%user_avatar%" class="img-thumbnail" style="width:100%;height:120px;" />
                        <p align="center">
                            <a href="%profile_url%" >%username%</a>
                        </p>
                        Date Joined:<br />
                        <small class="text-muted pull-right">
                            %date_joined%
                        </small><br />
                        Posts:<br />
                        <small class="text-muted pull-right">
                            %postcount%
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                %reply_content%
                <div class="post_tools">
                    %quote_post%
                    %edit_post%
                    %report_post%
                </div>
                <div style="border-bottom:1px dashed #e1e1e1;"></div>
                <div class="signature">
                    %user_signature%
                </div>
                <small class="text-muted">Posted %post_time%</small>
                <div class="mod_tools">
                    %mod_tools%
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!--- tpl:thread_reply:end -->
<!--- tpl:reply_thread:start -->
<div id="reply">
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
    <td style="width:70%;">%node_name%<br /><small>%node_desc%</small></td>
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
<p class="pull-left">
    %breadcrumbs%
</p>
<p class="pull-right">
    %post_thread_button%
</p>
<table class="table table-striped">
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
<!--- parent:theme_entity_file:end -->