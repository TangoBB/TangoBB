<?php

define('BASEPATH', 'Staff');
require_once('../applications/wrapper.php');

if (!$TANGO->perm->check('access_administration')) {
    redirect(SITE_URL);
}//Checks if user has permission to create a thread.
require_once('template/top.php');

echo '<div class="col-md-12">
        <div class="page-header">
          <h1>Administration Panel</h1>
        </div>
      </div>';

$versions = @file_get_contents('http://api.codetana.com/iko/version_list.php'); //@jtPox insert the real IP here
if ($versions != '') {
    $versionList = explode("|", $versions);
    foreach ($versionList as $version) {
        if (version_compare(TANGOBB_VERSION, $version, '<')) {
            $alert = $ADMIN->alert('<p>New version found: ' . $version . '<br /><a href="' . SITE_URL . '/admin/update.php?doUpdate=true&step=1">&raquo; Download Now?</a></p>', 'warning');
        }
    }
}
echo $ADMIN->box(
    'Dashboard',
    'This forum is powered by TangoBB <strong>' . TANGOBB_VERSION . '</strong>.' . @$alert,
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
    'Fork TangoBB on Github <a href="https://github.com/Codetana/TangoBB">here</a>.<br />
       To keep up with the updates on TangoBB, you can fork/watch the TangoBB Github repository or visit our website at <a href="http://tangobb.com">TangoBB.Com</a> regularly!'
);

require_once('template/bot.php');

?>
