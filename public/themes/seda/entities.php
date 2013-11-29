<!--- parent:theme_entity_file:start -->
<!--- tpl:quote_post:start -->
<blockquote id="post_quote"><p>%quoted_post_content%</p><small class="quote_username">%quoted_post_user%</small></blockquote>
<!--- tpl:quote_post:end -->
<!--- tpl:thread_closed:start -->
<span class="locked-thread right" title="Thread Closed"></span>
<!--- tpl:thread_closed:end -->
<!--- tpl:thread_stickied:start -->
<span class="sticky-thread right" title="Thread Stickied"></span>
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
<ul class="pagination right">
    %pages%
</ul>
<!--- tpl:pagination:end -->
<!--- tpl:pagination_links:start -->
<li><a href="%url%">%page%</a></li>
<!--- tpl:pagination_links:end -->
<!--- tpl:pagination_link_current:start -->
<li class="active"	><a href="#">%page%</a></li>
<!--- tpl:pagination_link_current:end -->
<!--- tpl:members_page:start -->
<div id="wrap">
    <div id="member">
		<img class="member_list_avatar" src="%avatar%"/>
		<span class="details">
			<span class="left_details">
				<a href="%profile_url%" class="member_list_link">%username%</a>
			</span>
			<span class="right_details">
				Joined: %date_joined%<br>
				Messages: %postcount%
			</span>
		</span>
	</div>
</div>
<!--- tpl:members_page:end -->
<!--- tpl:thread_starter:start -->
<div>
	<div id="breadcrumbs">
		%breadcrumbs%
	</div>
    <div class="right">
        %reply_button%
    </div>
</div>

<div id="starter_post">
	<div id="member_card">
		<img src="%user_avatar%" class="user_avatar s4"/>
		<a href="%profile_url%" class="username"> %username% </a>
		Messages: %postcount%
	</div>
	<div id="post_content">
		<div class="post_content">
		%thread_content%
		</div>
		<div id="user_signature">
			%user_signature%
		</div>
		<div id="post_details">
			<div class="left">
				Posted %post_time%
			</div>
			<div class="right">
				%quote_post% &nbsp;
				%edit_post% &nbsp;
				%report_post% &nbsp;
				%mod_tools% &nbsp;
			</div>
		</div>
	</div>
</div>
<hr style="margin: 15px 0px 18px;">

<!--- tpl:thread_starter:end -->
<!--- tpl:thread_reply:start -->
<div id="reply_post">
	<div id="%post_id%">
	 <div id="member_card">
			<img src="%user_avatar%" class="user_avatar s4"/>
			<a href="%profile_url%" class="username"> %username% </a>
			Messages: %postcount%
		</div>
		<div id="post_content">
			<div class="reply_content">
				%reply_content%
			</div>
			<div id="user_signature">
				%user_signature%
			</div>
			<div id="post_details">
				<div class="left">
					Posted %post_time%
				</div>
				<div class="right">
					%quote_post% &nbsp;
					%edit_post% &nbsp;
					%report_post% &nbsp;
					%mod_tools% &nbsp;
				</div>
			</div>
		</div>
	</div>
</div>
<!--- tpl:thread_reply:end -->
<!--- tpl:reply_thread:start -->
<hr>
<div id="reply">
    <div class="panel-body">
        <form id="%form_id%" action="%reply_form_action%" method="POST">
            %csrf_input%
					<textarea id="%editor_id%" class="post_reply_textarea" name="%textarea_name%"></textarea>
            <div class="right">
                <input type="submit" class="button medium green" name="%submit_name%" value="Post Reply" />
            </p>
        </form>
    </div>
</div>
<!--- tpl:reply_thread:end -->
<!--- tpl:forum_listings_category:start -->
<div id="node_section">
    <div class="node_category">
		<span class="category_name"> %category_name% </span>
		<span class="category_desc"> %category_desc% </span>	
	</div>
	%category_forums%
</div>
<!--- tpl:forum_listings_category:end -->
<!--- tpl:forum_listings_node:start -->
<div id="node">
    <span class="node_details">
		<div class="node_name">%node_name%</div>
		<div class="node_desc">%node_desc%</div>
	</span>
    <span class="latest_post">%latest_post%</span>
</div>
<!--- tpl:forum_listings_node:end -->
<!--- tpl:forum_listings_node_latest:start -->
<div class="latest">
	<span class="image"><img src="%user_avatar%" class="latest_avatar left" /></span>
	<span class="details"><div class="newest_post">Latest: %latest_post%</div>
	<div class="post_user">%post_user%, %post_time%</div></span>
</div>

<!--- tpl:forum_listings_node_latest:end -->
<!--- tpl:forum_listings_node_threads:start -->

<span class="right">
    %post_thread_button%
</span>
<div id="threads">
	<div id="thread_labels">
		<span class="thread_label_left">Thread</span>
		<span class="thread_label_right">Last Post</span>
	</div>
        %threads%
</div>


<!--- tpl:forum_listings_node_threads:end -->
<!--- tpl:forum_listings_node_threads_posts:start -->
<div id="thread">
	<div class="profile_picture">
		<img src="%user_avatar%" class="user_avatar s2" />
	</div>
	<div class="details">
		<div class="name">%thread_name%</div>
		<span class="user">%user%, %post_time%</span>
	</div>
	<div class="latest" style="margin-top: 10px;">
		%latest_post%
	</div>
</div>
<!--- tpl:forum_listings_node_threads_posts:end -->
<!--- tpl:forum_listings_node_threads_latestreply:start -->
<span class="latest_user_avatar"><img src="%user_avatar%" class="user_avatar s1 left"/></span>
<span class="latest_user_details">
	<div class="latest_username">%post_user%</div>
	<span class="latest_time">%post_time%</span>
</span>
<!--- tpl:forum_listings_node_threads_latestreply:end -->
<!--- tpl:create_thread:start -->
<div id="page_content">
	<form id="%form_id%" class="create_thread" action="%create_thread_form_action%" method="POST">
		%csrf_input%
		<input type="text" name="%title_name%" placeholder="Thread Title..." class="create_thread_title" />
		<br />
		<textarea id="%editor_id%" class="create_thread_textarea" name="%textarea_name%"></textarea>
		<div class="center-block" style="margin-top:5px;">
			<input type="submit" name="%submit_name%" value="Create Thread" class="button medium green right" />
			<input type="button" onClick="parent.history.go(-1);" class="button medium red left" value="Cancel"/>
		</div>
	</form>
</div>
<!--- tpl:create_thread:end -->
<!--- tpl:reply_thread_page:start -->
%quote_post%
<form id="%form_id%" action="%create_thread_form_action%" method="POST">
    %csrf_input%
    <textarea id="%editor_id%" name="%textarea_name%" class="quote_post_textarea"></textarea>
    <div class="center-block" style="margin-top:5px;">
        <input type="submit" name="%submit_name%" value="Reply" class="button medium green right" />
        <a href="%thread_url%" class="button medium red left">Return</a>
    </div>
</form>
<!--- tpl:reply_thread_page:end -->
<!--- tpl:user_profile_page:start -->
<div id="user_profile_container">
	<div class="profile_avatar">
		<img class="profile_avatar" src="%user_avatar%" />
		<div class="usergroup_badge">%usergroup%</div>
	</div>
	<div class="profile_username">%username%</div>
	
	<b>Joined:</b> %registered_date%
	<div class="ban_user">%mod_tools%</div>
<hr>
	<div class="information">
		<div id="tabs">
			<input type="radio" name="tabs_one" id="tab1" checked="checked" />
			<label for="tab1">Information</label>
		  
			<input type="radio" name="tabs_one" id="tab2"/>
			<label for="tab2">Recent Activity</label>
			  
			<br/>
		  
			<div class="tab1">
				<div class="title">Signature:</div>
				<pre class="signature">%user_signature%</pre>
			</div>
			<div class="tab2">
				<div class="activity">%recent_activity%</div>
			</div>
			
		</div>	
	</div>
</div>

<!--- tpl:user_profile_page:end -->
<!--- tpl:search_page:start -->
<div id="search_content">
	<div id="tabs">
		<input type="radio" name="tabs_one" id="tab1" checked="checked" />
		<label for="tab1">Threads</label>
			  
		<input type="radio" name="tabs_one" id="tab2"/>
		<label for="tab2">Users</label>
				  
		<br/>
			  
		<div class="tab1">
			<div id="search_threads">%searched_threads%</div>
		</div>
		<div class="tab2">
			<div id="search_users">%searched_users%</div>
		</div>
	</div>
</div>
<!--- tpl:search_page:end -->
<!--- tpl:mod_reports:start -->
<div id="mod_content">
	<div id="tabs">
		<input type="radio" name="tabs_one" id="tab1" checked="checked" />
		<label for="tab1">Reported Posts</label>
			  
		<input type="radio" name="tabs_one" id="tab2"/>
		<label for="tab2">Reported Users</label>
				  
		<br/>
			  
		<div class="tab1">
			<div id="reported_threads">%reported_posts%</div>
		</div>
		<div class="tab2">
			<div id="reported_users">%reported_users%</div>
		</div>
	</div>
</div>
<!--- tpl:mod_reports:end -->
<!--- parent:theme_entity_file:end -->