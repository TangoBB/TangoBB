<?php

/*
 * TangoBB PermGET Class.
 */

if (!defined('BASEPATH')) {
    die();
}

class Library_PermGET
{

    private $file;
    public $error;

    /*
     * Basic configuration.
     */
    public function _construct()
    {
        $this->file = $_SERVER['REQUEST_URI'];
    }

    /*
     * The PermGET Function.
     * example.php/parameter/value
     */
    public function g($param, $callback = null)
    {
        if (stristr($_SERVER['REQUEST_URI'], '/' . $param . '/')) {
            $value = $this->getBetween($_SERVER['REQUEST_URI'], '/' . $param . '/', '/');
            if (is_callable($callback)) {
                call_user_func($callback, $value);
            } else {
                return $value;
            }
        } else {
            $this->error[] = 'Undefined permGET parameter.';
            return false;
        }
    }

    /*
     * The PermGET function for single value and no parameter.
     * - $with_id false: example.php/value
     * - $with_id true: example.php/value.id
     */
    public function s($with_id = false)
    {
        //$url        = ($this->isSSL())? 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $url        = SITE_URL . $_SERVER['REQUEST_URI'];
        list($page) = sscanf(
            $url,
            SITE_URL . '/%s'
        );
        preg_match_all('/(.*).php/', $page, $verify);
        //die(var_dump($verify['0']['0']));
        $isset = str_replace($verify['0']['0'], '', $page);
        if (!empty($isset)) {
            $value = preg_replace('/(.*).php\//', '', $page);
            if ($with_id) {
                preg_match_all('/(.*)\.([0-9]+)/', $value, $values);
                //die(var_dump($values));
                $values = array(
                    //'value' => str_replace('.', '', $values['1']['0']),
                    'value' => clean($values['1']['0']),
                    'id' => clean($values['2']['0'])
                );
                return $values;
            } else {
                return $value;
            }
        } else {
            $this->error[] = 'No object defined.';
            return false;
        }
        //$value =  preg_replace('/(\w+).php\//', '', $page);
    }

    /*
     * Get between strings.
     */
    private function getBetween($content, $start, $end)
    {
        $r = explode($start, $content);
        if (isset($r[1])) {
            $r = explode($end, $r[1]);
            return $r[0];
        } else {
            return '';
        }
    }

    /*
     * Check if the server is using a secure connection.
     */
    function isSSL() {
        if( isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ) {
            return true;
        } else {
            return false;
        }
    }

}

?>
