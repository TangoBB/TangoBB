<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');
  
  $versions = @file_get_contents('http://127.0.0.1/update_packages/version_list.php'); //@jtPox insert the real IP here
  if($versions != '') 
  {
        $versionList = explode("|", $versions);
        foreach($versionList as $version) 
        {
            if( version_compare(TANGOBB_VERSION, $version, '<') ) 
            {
                $alert = $ADMIN->alert('<p>New version found: ' . $version . '<br /><a href="'.SITE_URL.'/admin/update.php?doUpdate=true&step=1">&raquo; Download Now?</a></p>','warning');
            }
        }
  }
  echo $ADMIN->box(
      'Dashboard',
      'This forum is powered by Iko <strong>' . TANGOBB_VERSION . '</strong>.'.@$alert,
      '<table class="table">
         <thead>
           <tr>
             <th>Forum Statistic</th>
              <th>Value</th>
            </tr>
         </thead>
         <tbody>
           <tr>
             <td>Threads</td>
             <td>' . stat_threads() . '</td>
           </tr>
          <tr>
             <td>Posts</td>
             <td>' . stat_posts() . '</td>
           </tr>
           <tr>
             <td>Users</td>
             <td>' . stat_users() . '</td>
           </tr>
        </tbody>
       </table>'
  );

  echo $ADMIN->box(
      'Github and Updates',
      'Fork Iko on Github <a href="https://github.com/IkoBulletin/Iko">here</a>.<br />
       To keep up with the updates on Iko, you can fork/watch the Iko Github repository or visit our website at <a href="http://iko.im">Iko.Im</a> regularly!'
  );

  require_once('template/bot.php');

?>