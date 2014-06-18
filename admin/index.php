<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_administration') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  require_once('template/top.php');

  echo $ADMIN->box(
      'Dashboard',
      'This forum is powered by TangoBB <strong>' . TANGOBB_VERSION . '</strong>.',
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