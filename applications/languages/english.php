<?php

  /*
   * TangoBB Language Entity.
   * Language: English.
   */

  $LANG = array(
  	//Main Forum
  	'bb' => array(
  		'forum' => 'Forum',
  		'members' => 'Members',
  		'search' => 'Search',
  		'edit_post_in' => 'Edit post in:',
  		'new_thread_in' => 'New thread in:',
  		'new_reply_in' => 'New reply in:',
  		'new_report' => 'Report',
  		'new_thread_breadcrumb' => 'New Thread',
  		'edit_post_breadcrumb' => 'Edit Post',
  		'new_reply_breadcrumb' => 'New Reply',
  		'form' => array(
  			'edit_post' => 'Edit Post',
  			'report' => 'Report',
  			'report_reason' => 'Reason'
  		),
  		'conversations' => array(
  			'by' => 'By:',
  			'for' => 'For:',
  			'form_to'=> 'To <small>Seperated with a ","</small>',
  			'form_send' => 'Send',
  			'form_title' => 'Title',
  			'form_reply' => 'Reply',
  			'no_conversations' => 'No conversations yet.',
  			'user_not_exist' => 'User <strong>%username%</strong> does not exist!',
  			'message_sent' => 'Your message to <strong>%username%</strong> has been sent!',
  			'error_sending' => 'Error sending message to <strong>%username%</strong>.',
  			'error_sending_alt' => 'Error sending message.'
  		),
  		'members' => array(
        'home' => 'Members',
        'activate_account' => 'Activate Account',
        'account_activated' => 'Your account has been activated! <a href="' . SITE_URL . '/members.php/cmd/signin">Sign in</a> now.',
        'error_activating' => 'Error activating account. Try again later.',
        'forgot_password' => 'Forgot Password',
        'reset_password' => 'Reset Password',
        'error_request_password_reset' => 'Failed to create password reset request.',
        'form_reset_password' => 'Reset Password',
        'form_email' => 'Email',
        'form_password' => 'Password',
        'form_confirm_password' => 'Confirm Password',
        'form_username' => 'Username',
        'form_register' => 'Register',
        'password_reset_link_sent' => 'A link for the password reset has been sent to your email account.',
        'error_password_reset_token_used' => 'The password reset token has already been used. Please request a new password reset.',
        'error_password_reset_token_expired' => 'The password reset token has expired. Please request a new password reset.',
        'error_password_reset_token_unknown' => 'Unknown password reset token. Please use the URL from the email.',
        'error_password_reset_token_missing' => 'Missing password reset token in URL. Please use the URL from the email.',
        'error_password_reset' => 'Failed to change the password.',
        'password_reset_successful' => 'Your password has been reset.',
        'register' => 'Register',
        'password_different' => 'Password is different!',
        'username_taken' => 'Username is already taken!',
        'error_register' => 'Error registering you, please try again later.',
        'register_successful' => 'Successfully registered! Now logging you in...',
        'register_successful_email' => 'Successfully registered! An email has been sent to be verified.',
        'register_message' => 'By clicking "Register", you agree to abide by the forum rules located <a href="' . SITE_URL . '/members.php/cmd/rules">here</a>.',
        'log_in' => 'Sign In',
        'invalid_login' => 'Invalid details!',
        'login_success' => 'Successfully logged in! Click <a href="' . SITE_URL . '">here</a> if the page does not redirect you.',
        'email_not_activated' => 'Your email has not been activated yet.',
        'banned' => 'You are currently banned. Contact staff for details.<br />Unban Date: <b>%unban_date%</b><br />Ban Reason: <b>%ban_reason%</b>',
        'rules' => 'Forum Rules',
        'rules_message' => 'All users are to abide by the forum rules.<br />%rules%<br />Breaking the rules may result in your post being removed or edited, repeatedly breaking rules may result in a temporary or permanent ban.',
        'profile_of' => 'Profile of',
        'posted_thread' => 'Posted a new thread <a href="%url%">%title%</a> <small>(%date%)</small><hr size="1" />',
        'replied_to' => 'Replied to the thread <a href="%url%">%title%</a> <small>(%date%)</small><hr size="1" />'
  		),
      'profile' => array(
        'avatar' => 'Avatar',
        'change_avatar' => 'Change Avatar <small>A maximum of 500x500 Pixels</small>',
        'use_gravatar' => 'Use Gravatar Instead',
        'form_save' => 'Save Changes',
        'error_adding_gravatar' => 'Error adding Gravatar. Try again later.',
        'successful_adding_gravatar' => 'Gravatar successfully saved!',
        'error_upload_avatar' => 'Error uploading avatar. Try again later.',
        'successful_upload_avatar' => 'Avatar suceessfully saved!',
        'password' => 'Password',
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'error_updaing_password' => 'Error updating password.',
        'signature' => 'Signature',
        'error_updating_signature' => 'Error updating signature. Try again later.',
        'personal_details' => 'Personal Details',
        'confirm_password' => 'Confirm Password'
      )
  	),
  	//Global Form Variables
  	'global_form_process' => array(
  		'all_fields_required' => 'All fields are required!',
  		'enter_search_query' => 'Please enter a search query!',
  		'error_updating_post' => 'Error updating post. Try again later.',
  		'error_creating_thread' => 'Error creating thread. Try again later.',
  		'error_replying_thread' => 'Error replying to thread. Try again later.',
  		'error_submitting_report' => 'Error submitting report. Try again later.',
  		'thread_create_success' => 'Successfully created thread! Redirecting you...',
  		'report_create_success' => 'Report has been successfully submitted!',
  		'search_no_result' => 'No results.',
  		'different_message_previous' => 'Please write a different message from your previous post.',
      'email_not_exist' => 'Email does not exist in our records!',
      'email_used' => 'Email is used, please use a new one.',
      'invalid_email' => 'Email is not valid!',
      'invalid_file_format' => 'Invalid file format!',
      'img_dimension_limit' => 'Image dimension too big!',
      'save_success' => 'Saved!',
      'error_saving' => 'Error saving. Try again later.',
      'invalid_password' => 'Current password is invalid!'
    ),
  	//Moderator Panel
  	'mod' => array(
      'ban' => array(
        'ban' => 'Ban User',
        'ban_success' => 'User has been banned. <a href="%url%">Back to user profile</a>.',
        'ban_error' => 'Error banning user.',
        'already_banned' => 'User is already banned.',
        'unban' => 'Unban User',
        'unban_success' => 'User has been unbanned. <a href="%url%">Back to user profile</a>.',
        'unban_error' => 'Error unbanning user.',
        'already_unbanned' => 'User is already unbanned.'
      ),
      'close' => array(
        'close' => 'Close Thread',
        'close_success' => 'Thread has been closed. <a href="%url%">Back to thread</a>.',
        'close_error' => 'Error closing thread.',
        'already_closed' => 'Thread is already closed.',
        'open' => 'Open Thread',
        'open_success' => 'Thread has been opened. <a href="%url%">Back to thread</a>.',
        'open_error' => 'Error opening user.',
        'already_opened' => 'Thread is already opened.'
      ),
      'stick' => array(
        'stick' => 'Stick Thread',
        'stick_success' => 'Thread has been stuck. <a href="%url%">Back to thread</a>.',
        'stick_error' => 'Error sticking thread.',
        'already_stuck' => 'Thread is already stuck.',
        'unstick' => 'Unstick Thread',
        'unstick_success' => 'Thread has been unstuck. <a href="%url%">Back to thread</a>.',
        'unstick_error' => 'Error sticking thread.',
        'already_unstuck' => 'Thread is already unstuck.'
      ),
      'reports' => array(
        'reports' => 'Reports',
        'thread' => 'Thread:',
        'user' => 'User:',
        'reason' => 'Reason:',
        'reported_time' => 'Reported Time:'
      )
    ),
  	//Administrator Panel (Coming Soon)
  	'admin' => array()
  );

?>