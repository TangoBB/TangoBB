<?php

/*
 * Admin class of TangoBB
 */
if (!defined('BASEPATH')) {
    die();
}

class Tango_Admin
{

    private $links = array();

    public function __construct()
    {
        //Adding default navigation for ACP.
        $this->addNav(
            'Configuration',
            array(
                'General' => SITE_URL . '/admin/general.php',
                'Extensions' => SITE_URL . '/admin/extensions.php'
            )
        );
        $this->addNav(
            'Forum',
            array(
                'Manage Categories' => SITE_URL . '/admin/manage_category.php',
                'Manage Nodes' => SITE_URL . '/admin/manage_node.php'
            )
        );
        $this->addNav(
            'Customization',
            array(
                'Usergroups' => SITE_URL . '/admin/usergroups.php',
                'Theme' => SITE_URL . '/admin/theme.php'
            )
        );
    }

    /*
     * Function for adding a navigation link in the ACP
     */
    public function addNav($name, $links = array())
    {
        $this->links[$name] = array(
            'name' => $name,
            'links' => array()
        );
        foreach ($links as $value => $href) {
            $this->links[$name]['links'][] = array(
                'value' => $value,
                'href' => $href
            );
        }
    }

    /*
     * Adding a content box in ACP.
     */
    public function box($header = null, $content, $table = "", $column = "6")
    {
        $columns = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
        $column = (in_array($column, $columns)) ? $column : '6';
        $header = ($header == null) ? '' : '<div class="panel-heading"><strong>' . $header . '</strong></div>';
        $return = '<div class="col-md-' . $column . '">
                          <div class="panel panel-default">
                              ' . $header . '
                              <div class="panel-body">
                                ' . $content . '
                              </div>
                              ' . $table . '
                          </div>
                      </div>';
        return $return;
    }

    /*
     * Notification.
     */
    public function alert($content, $type = "info")
    {
        $types = array('success', 'info', 'warning', 'danger');
        $type = (in_array($type, $types)) ? $type : 'info';
        return '<div class="alert alert-' . $type . '">' . $content . '</div>';
    }

    /*
     * Display the ACP navigation.
     */
    public function navigation()
    {
        $return = '';
        foreach ($this->links as $link) {
            $return .= '<li class="dropdown">
                            <a href="javascript:return false;" class="dropdown-toggle" data-toggle="dropdown">' . $link['name'] . ' <span class="caret pull-right" style="margin-top:10px;"></span></a>
                             <span class="dropdown-arrow dropdown-arrow-inverse"></span>
                            <ul class="dropdown-menu dropdown-inverse">';
            foreach ($link['links'] as $page) {
                $return .= '<li><a href="' . $page['href'] . '">' . $page['value'] . '</a></li>';
            }

            $return .= '</ul>
                          </li>';
        }
        return $return;
    }

    public function zip_extract($file_input, $report = false, $update = false)
    {
        $zipHandle = zip_open($file_input);
        $i = 0;
        $file_message = array();
        $error = array();
        var_dump($zipHandle);
        while ($file = zip_read($zipHandle)) {
            $i++;
            $thisFileName = zip_entry_name($file);
            $thisFileDir = dirname($thisFileName);

            if (!zip_entry_open($zipHandle, $file, 'r')) {
                $error[$i] = 'File could not be handled: ' . $thisFileName . '<br />';
                continue;
            }
            if (!is_dir($thisFileDir)) {
                $file_message[$i] = '<li>' . $thisFileDir . ': ';
                mkdir($thisFileDir, 0755);
            }
            $zip_filesize = zip_entry_filesize($file);
            if (empty($zip_filesize)) {
                if (substr($thisFileName, -1) == '/') {
                    $file_message[$i] = '<li>' . $thisFileName . ': ';
                    if (!is_dir('../' . $thisFileName)) {
                        mkdir('../' . $thisFileName, 0755);
                    }

                    unset($thisFileDir);
                    unset($thisFileName);
                    continue;
                }
            }
            $content = zip_entry_read($file, $zip_filesize);

            if ($thisFileName == 'upgrade.php' && $update === true) {
                $file_message[$i] = '<li>' . $thisFileName . ': ';
                if (@file_put_contents('updates/' . $thisFileName, $content) === false) {
                    $error[$i] = 'File could not be handled: ' . $thisFileName . '<br />';
                }
            } else {
                $file_message[$i] = '<li>' . $thisFileName . ': ';
                if (@file_put_contents('../' . $thisFileName, $content) === false) {
                    $error[$i] = '#2 File could not be handled: ' . $thisFileName . '<br />';
                }
            }
            zip_entry_close($file);
            unset($thisFileDir);
            unset($thisFileName);
        }
        zip_close($zipHandle);
        if ($report === true) {
            $output = '<ul>';
            foreach ($file_message as $i => $message) {
                $output .= $message;
                if (@$error[$i] == '') {
                    $output .= '-> Done';
                } else {
                    $output .= $error[$i];
                }
                $output .= '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }

    public function download($link, $update = false)
    {
        $file_name = basename($link);
        if (@fopen($link, 'r')) {
            if ($update === true && !is_file('updates/' . $file_name)) {
                $file = curl_init($link);
                if (!is_dir('updates/')) mkdir('updates/');
                $dlHandler = fopen('updates/' . $file_name, 'w');
                curl_setopt($file, CURLOPT_FILE, $dlHandler);
                curl_setopt($file, CURLOPT_TIMEOUT, 3600);
                curl_exec($file);
                fclose($dlHandler);
            } elseif (!is_file('downloads/' . $file_name)) {
                $file = curl_init($link);
                if (!is_dir('downloads/')) mkdir('downloads/');
                $dlHandler = fopen('downloads/' . $file_name, 'w');
                curl_setopt($file, CURLOPT_FILE, $dlHandler);
                curl_setopt($file, CURLOPT_TIMEOUT, 3600);
                curl_exec($file);
                fclose($dlHandler);
            }
            return $file_name;
        } else {
            return false;
        }
    }

}

?>