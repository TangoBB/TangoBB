<?php

define('BASEPATH', 'Staff');
require_once('../applications/wrapper.php');

if (!$TANGO->perm->check('access_administration')) {
    redirect(SITE_URL);
}

require_once('template/top.php');

$notice = '';

if( isset($_POST['upload']) ) {
    try {

        $file = $_FILES['entity']['tmp_name'];

        if( !$file ) {
            throw new Exception ('Please upload an entity file.');
        } else {

            $json = file_get_contents($file);
            $json = json_decode($json, true);

            $name = $json['theme_name'];
            $ver  = $json['theme_version'];
            $ent  = $json['entities'];
            $btn  = $json['buttons'];

            $sql_ent = array(
                'entities' => $ent,
                'buttons' => $btn
            );
            $sql_ent = json_encode($sql_ent, JSON_PRETTY_PRINT);

            $MYSQL->bindMore(
                array(
                    'theme_name' => $name,
                    'theme_version' => $ver,
                    'theme_json_data' => $sql_ent
                )
            );

            $query = $MYSQL->query("INSERT INTO
                                    {prefix}themes
                                    (theme_name, theme_version, theme_json_data)
                                    VALUES
                                    (:theme_name, :theme_version, :theme_json_data)");

            if( $query ) {
                $notice .= $ADMIN->alert(
                    '<strong>' . $name . '</strong> theme successfully uploaded!',
                    'success'
                );
            } else {
                throw new Exception ('Error uploading theme.');
            }

        }

    } catch (Exception $e) {
        $notice .= $ADMIN->alert(
            $e->getMessage(),
            'danger'
        );
    }
}

echo $ADMIN->box(
    'New Theme',
    $notice . '<form action="" method="POST" enctype="multipart/form-data">
       <label for="entity">Entity File</label> <input type="file" name="entity" id="entity" class="form-control" /><br />
       <input type="submit" name="upload" value="Upload" class="btn btn-default" />
     </form>',
    '',
    6
);

echo $ADMIN->box(
    'Entity File',
    'The entity is a JSON file that is <strong>required</strong> in in order to make a theme work on TangoBB.',
    '',
    '6'
);

require_once('template/bot.php');

?>
