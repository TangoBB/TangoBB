<?php

define('BASEPATH', 'Staff');
require_once('../applications/wrapper.php');

if (!$TANGO->perm->check('access_administration')) {
    redirect(SITE_URL);
}//Checks if user has permission to create a thread.
require_once('template/top.php');

if( $PGET->g('theme') ) {

    $MYSQL->bind('theme_name', $PGET->g('theme'));
    $query = $MYSQL->query("SELECT * FROM {prefix}themes WHERE id = :theme_name");

    if( !empty($query) ) {

        $notice = '';

        if( isset($_POST['edit']) ) {
            try {

                $tpl  = array();
                foreach( $_POST['tpl'] as $name => $value ) {
                    $tpl[$name] = html_entity_decode($value, ENT_NOQUOTES);
                }

                $ent  = array();
                foreach( $_POST['ent'] as $name => $value ) {
                    $ent[$name] = html_entity_decode($value, ENT_NOQUOTES);
                }

                $btn  = array();
                foreach( $_POST['btn'] as $name => $value ) {
                    $btn[$name] = html_entity_decode($value, ENT_NOQUOTES);
                }

                $json = json_encode(
                    array(
                        'templates' => $tpl,
                        'entities' => $ent,
                        'buttons' => $btn
                    )
                );

                $MYSQL->bind('theme_json_data', $json);
                $MYSQL->bind('id', $query['0']['id']);
                $edit = $MYSQL->query("UPDATE {prefix}themes SET theme_json_data = :theme_json_data WHERE id = :id");
                if( $edit ) {
                    $notice .= $ADMIN->alert(
                        'Theme have been successfully edited!',
                        'success'
                    );
                } else {
                    throw new Exception ('Error editing theme.');
                }

            }catch( Exception $e ) {
                $notice .= $ADMIN->alert(
                    $e->getMessage(),
                    'danger'
                );
            }
        }

        $json   = json_decode($query['0']['theme_json_data'], true);

        $tpl    = '';
        foreach( $json['templates'] as $name => $value ) {
            $value = htmlentities($value, ENT_NOQUOTES);
            $tpl .= '<div class="panel panel-default">
                       <div class="panel-heading" role="tab" id="headingOne">
                         <h4 class="panel-title">
                           <a data-toggle="collapse" data-parent="#accordion" href="#' . $name . '-tpl-acc" aria-expanded="true" aria-controls="collapseOne">
                             ' . $name . '
                           </a>
                         </h4>
                       </div>
                       <div id="' . $name . '-tpl-acc" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                       <div class="panel-body">
                         <textarea name="tpl[' . $name . ']" class="form-control highlight" style="min-height:200px;">
' . $value . '</textarea>
                       </div>
                     </div>
                   </div>';
        }

        $ent    = '';
        foreach( $json['entities']as $name => $value ) {
            $value = htmlentities($value, ENT_NOQUOTES);
            $ent .= '<div class="panel panel-default">
                       <div class="panel-heading" role="tab" id="headingOne">
                         <h4 class="panel-title">
                           <a data-toggle="collapse" data-parent="#accordion" href="#' . $name . '-ent-acc" aria-expanded="true" aria-controls="collapseOne">
                             ' . $name . '
                           </a>
                         </h4>
                       </div>
                       <div id="' . $name . '-ent-acc" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                       <div class="panel-body">
                         <textarea name="ent[' . $name . ']" class="form-control highlight" style="min-height:200px;">
' . $value . '</textarea>
                       </div>
                     </div>
                   </div>';
        }

        $btn     = '';
        foreach( $json['buttons']as $name => $value ) {
            $value = htmlentities($value, ENT_NOQUOTES);
            $btn .= '<div class="panel panel-default">
                       <div class="panel-heading" role="tab" id="headingOne">
                         <h4 class="panel-title">
                           <a data-toggle="collapse" data-parent="#accordion" href="#' . $name . '-btn-acc" aria-expanded="true" aria-controls="collapseOne">
                             ' . $name . '
                           </a>
                         </h4>
                       </div>
                       <div id="' . $name . '-btn-acc" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                       <div class="panel-body">
                         <textarea name="btn[' . $name . ']" class="form-control highlight" style="min-height:200px;">
' . $value . '</textarea>
                       </div>
                     </div>
                   </div>';
        }

        echo '<form action="" method="POST">';

        echo $ADMIN->box(
            'Edit Theme (' . $query['0']['theme_name'] . ')<span class="pull-right"><a href="' . SITE_URL . '/admin/theme.php" class="btn btn-default btn-xs">Back</a> <input type="submit" name="edit" value="Edit Theme" class="btn btn-primary btn-xs" /></span>',
            '<div class="panel-body">
            <b>Tips</b> <br>
              Press <code>F11</code> inside an editor for fullscreen editing and <code>ESC</code> to exit.
            </div>',
            '<div class="panel-body">' . $notice . '</div>',
            '12'
        );

        echo $ADMIN->box(
            'Templates',
            '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            ' . $tpl . '
             </div>',
            '',
            '12'
        );

        echo $ADMIN->box(
            'Entities',
            '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            ' . $ent . '
             </div>',
            '',
            '12'
        );

        echo $ADMIN->box(
            'Buttons',
            '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            ' . $btn . '
             </div>',
            '',
            '12'
        );

        echo $ADMIN->box(
            '',
            '<input type="submit" name="edit" value="Edit Theme" class="btn btn-primary pull-right" />',
            '',
            '12'
        );

        echo '</form>';

    } else {
        echo $ADMIN->alert(
            'Theme does not exist. <a href="' . SITE_URL . '/admin/theme.php">Back</a>',
            'danger'
        );
    }

} else {
    echo $ADMIN->alert(
        'Please specify a theme. <a href="' . SITE_URL . '/admin/theme.php">Back</a>',
        'danger'
    );
}

require_once('template/bot.php');
?>
