<?php

/*
 * Template class of TangoBB
 */
if (!defined('BASEPATH')) {
    die();
}

class Tango_Template
{

    private $output, $theme;
    private $params = array();
    private $ent_params = array();
    private $elapsed_time;
    private $pagination = array();
    private $breadcrumb = array();
    private $json_data;

    function __construct()
    {
        global $TANGO, $MYSQL;

        $this->elapsed_time = microtime(true);

        $this->addParam('site_url', SITE_URL);
        $this->addParam('site_name', $TANGO->data['site_name']);

        $this->addParam('bb_stat_threads', stat_threads());
        $this->addParam('bb_stat_posts', stat_posts());
        $this->addParam('bb_stat_users', stat_users());
        $this->addParam('bb_software_version', TANGOBB_VERSION);
        $this->addParam('users_online', users_online());
        $this->addParam('current_url', $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

        //Globally adding parameters for captcha.

        $this->addParam(
            'editor_settings',
            '<link rel="stylesheet" href="' . SITE_URL . '/public/css/tangobb.css" />
               <script type="text/javascript" src="' . SITE_URL . '/public/js/jquery.min.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/autosaveform.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/wysibb/jquery.wysibb.min.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/jquery.tagsinput.min.js"></script>
               <script type="text/javascript" src="' . SITE_URL . '/public/js/tangobb.js"></script>
               <link type="text/css" rel="Stylesheet" href="' . SITE_URL . '/public/js/highlighter/styles/shThemeDefault.css"/>
               <link type="text/css" rel="Stylesheet" href="' . SITE_URL . '/public/js/highlighter/styles/shCore.css"/>
               '
        );

        $this->addParam(
            'highlighter_footer',
            '<script type="text/javascript" src="' . SITE_URL . '/public/js/highlighter/shCore.js"></script>
             <script type="text/javascript" src="' . SITE_URL . '/public/js/highlighter/shAutoloader.js" ></script>
             <script type="text/javascript">
             SyntaxHighlighter.autoloader(
                [\'js\',\'jscript\',\'javascript\',\'' . SITE_URL . '/public/js/highlighter/shBrushJScript.js\'],
                [\'bash\',\'shell\',\'' . SITE_URL . '/public/js/highlighter/shBrushBash.js\'],
                [\'css\',\'' . SITE_URL . '/public/js/highlighter/shBrushCss.js\'],
                [\'xml\',\'' . SITE_URL . '/public/js/highlighter/shBrushXml.js\'],
                [\'sql\',\'' . SITE_URL . '/public/js/highlighter/shBrushSql.js\'],
                [\'c-sharp\',\'csharp\',\'' . SITE_URL . '/public/js/highlighter/shBrushCSharp.js\'],
                [\'cpp\',\'c\',\'' . SITE_URL . '/public/js/highlighter/shBrushCpp.js\'],
                [\'vb\',\'vbnet\',\'' . SITE_URL . '/public/js/highlighter/shBrushVb.js\'],
                [\'java\',\'' . SITE_URL . '/public/js/highlighter/shBrushJava.js\'],
                [\'php\',\'' . SITE_URL . '/public/js/highlighter/shBrushPhp.js\']
             );
            SyntaxHighlighter.all();
            </script>'
        );

        if( $this->theme = $TANGO->data['site_theme'] ) {
           $MYSQL->bind('theme_name', $TANGO->data['site_theme']);
        } else {

        }
        $query = $MYSQL->query("SELECT * FROM
                                {prefix}themes");
        $theme = array();
        foreach( $query as $t ) {
            $theme[$t['id']] = $t;
        }
        //die($this->theme);
        $chosen_theme = null;
        if( $TANGO->sess->isLogged ) {
            if( $TANGO->sess->data['chosen_theme'] == "0" ) {
                //$MYSQL->bind('id', $TANGO->data['site_theme']);
                //$t_query      = $MYSQL->query("SELECT * FROM {prefix}themes WHERE id = :id");
                $chosen_theme = $TANGO->data['site_theme'];
            } else {
                $chosen_theme = $TANGO->sess->data['chosen_theme'];
            }

            $MYSQL->bind('post_user', $TANGO->sess->data['id']);
            $user_post_count = $MYSQL->query("SELECT * FROM {prefix}forum_posts WHERE post_user = :post_user");
            $user_post_count = number_format(count($user_post_count));

            $mod_report_integer = modReportInteger();

            $this->addParam(
                array(
                    'username',
                    'username_style',
                    'user_avatar',
                    'user_post_count',
                    'mod_report_integer'
                ),
                array(
                    $TANGO->sess->data['username'],
                    $TANGO->sess->data['username_style'],
                    $TANGO->sess->data['user_avatar'],
                    $user_post_count,
                    $mod_report_integer
                )
            );

        } else {
            $chosen_theme = $TANGO->data['site_theme'];
        }
        //die(var_dump($theme['3']));
        if( !isset($theme[$this->theme]) ) {
            $this->json_data = json_decode($theme[$TANGO->data['site_theme']]['theme_json_data'], true);
        } elseif( $chosen_theme !== $TANGO->data['site_theme'] ) {
            $this->json_data = json_decode($theme[$chosen_theme]['theme_json_data'], true);
        } elseif( $chosen_theme == $TANGO->data['site_theme'] ) {
            $this->json_data = json_decode($theme[$TANGO->data['site_theme']]['theme_json_data'], true);
        }

        //$this->json_data = json_decode($query['0']['theme_json_data'], true);
    }

    /*
     * Set forum theme.
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /*
     * Add template parameter.
     */
    public function addParam($param, $value)
    {
        if (is_array($param) or is_array($value)) {
            foreach (array_combine($param, $value) as $p => $v) {
                $this->params['%' . $p . '%'] = $v;
            }
        } else {
            $this->params['%' . $param . '%'] = $value;
        }
    }

    /*
     * Adding entity parameters.
     */
    public function addEntParam($param, $value)
    {
        if (is_array($param) or is_array($value)) {
            foreach (array_combine($param, $value) as $p => $v) {
                $this->ent_params['%' . $p . '%'] = $v;
            }
        } else {
            $this->ent_params['%' . $param . '%'] = $value;
        }
    }

    /*
     * Echo a string without using the "echo" PHP function which will return an error on the template system.
     */
    public function push($string)
    {
        $this->output .= $string;
    }

    /*
     * Getting information from the theme's entities file.
     */
    public function entity($entity, $param = "", $value = "", $parent = "theme_entity_file", $blade = true)
    {
        global $TANGO, $MYSQL;

        $result = $this->json_data;

        switch ($parent) {
            case "theme_entity_file":
              $tpl = $result['entities'];
            break;

            case "buttons":
              $tpl = $result['buttons'];
            break;

            default:
            break;
        }

        $result = $tpl[$entity];

        $params = array();
        $values = array();
        if (is_array($param) or is_array($value)) {
            foreach (array_combine($param, $value) as $p => $v) {
                $v = str_replace(
                    array(
                        '{',
                        '}',
                        '@'
                    ),
                    array(
                        '&#123;',
                        '&#125;',
                        '&#64;'
                    ),
                    $v
                );
                $params[] = '%' . $p . '%';
                $values[] = $v;
            }
        } else {
            $value = str_replace(
                array(
                    '{',
                    '}',
                    '@'
                ),
                array(
                    '&#123;',
                    '&#125;',
                    '&#64;'
                ),
                $value
            );
            $params[] = '%' . $param . '%';
            $values[] = $value;
        }
        $params[] = '%site_url%';
        $values[] = SITE_URL;
        $result = str_replace($params, $values, $result);

        $ent_params = array();
        $ent_values = array();
        foreach ($this->ent_params as $parent => $child) {
            $ent_params[] = $parent;
            $ent_values[] = $child;
        }
        $result = str_replace($ent_params, $ent_values, $result);

        if ($blade) {
            ob_start();
            $result = $this->bladeSyntax($result);
            eval(' ?>' . $result . '<?php ');
            $result = ob_get_clean();
            if (ob_get_contents()) {
                ob_end_clean();
            }
        }

        return $result;
    }

    /*
     * Blade template Syntax.
     * Thanks to https://github.com/loic-sharma/laravel-template
     */
    private function bladeSyntax($string)
    {

        $syntax_blade = array(
            '/{{ (.*) }}/',
            '/{{-v (.*) = (.*) }}/',
            '/(\s*)@(if|elseif|foreach|for|while)(\s*\(.*\))/',
            '/(\s*)@(endif|endforeach|endfor|endwhile)(\s*)/',
            '/(\s*)@(else)(\s*)/',
            '/(\s*)@unless(\s*\(.*\))/'
        );
        $syntax_php = array(
            '<?php echo $1; ?>',
            '<?php $1 = $2; ?>',
            '$1<?php $2$3: ?>',
            '$1<?php $2; ?>',
            '$1<?php $2: ?>$3',
            '$1<?php if( ! ($2)): ?>'
        );
        $string = preg_replace($syntax_blade, $syntax_php, $string);

        //@empty
        $string = str_replace('@empty', '<?php endforeach; ?><?php else: ?>', $string);
        //@forelse
        $string = str_replace('@endforelse', '<?php endif; ?>', $string);
        //@endunless
        $string = str_replace('@endunless', '<?php endif; ?>', $string);

        return $string;
    }

    /*
     * Get the template file.
     */
    public function getTpl($template, $ret = false)
    {
        global $TANGO;

        if( isset($this->json_data['templates'][$template]) ) {
            $return = $this->bladeSyntax($this->json_data['templates'][$template]);
            ob_start();
            eval('?>' . $return . '<?php');
            $return = ob_get_clean();
            ob_end_clean();

            if( !$ret ) {
                $this->output .= $return;
            } else {
                return $return;
            }

        } else {
            die('Template doesn\'t exist');
        }

    }

    /*
     * Pagination
     */
    public function addPagination($value, $link, $current = false)
    {
        if ($current) {
            $page = $this->entity(
                'pagination_link_current',
                'page',
                $value
            );
        } else {
            $page = $this->entity(
                'pagination_links',
                array(
                    'url',
                    'page'
                ),
                array(
                    $link,
                    $value
                )
            );
        }
        $this->pagination[] = $page;
    }

    public function pagination()
    {
        if (!empty($this->pagination)) {
            $pagination = '';
            foreach ($this->pagination as $page) {
                $pagination .= $page;
            }

            $return = $this->entity(
                'pagination',
                'pages',
                $pagination
            );
            return $return;
        } else {
            return false;
        }
    }

    /*
     * Breadcrumbs
     */
    public function addBreadcrumb($value, $link, $current = false)
    {
        if ($current) {
            $page = $this->entity(
                'breadcrumbs_current',
                'name',
                $value
            );
        } else {
            $page = $this->entity(
                'breadcrumbs_before',
                array(
                    'link',
                    'name'
                ),
                array(
                    $link,
                    $value
                )
            );
        }
        $this->breadcrumb[] = $page;
    }

    public function breadcrumbs()
    {
        if (!empty($this->breadcrumb)) {
            $breadcrumbs = '';
            foreach ($this->breadcrumb as $crumb) {
                $breadcrumbs .= $crumb;
            }

            $return = $this->entity(
                'breadcrumbs',
                'bread',
                $breadcrumbs
            );
            return $return;
        } else {
            return false;
        }
    }

    /*
     * Replacing all parameters with the values and outputs them.
     */
    public function output()
    {
        global $MYSQL;

        if (function_exists('memory_get_usage')) {
            $this->addParam('memory_usage', bytesToSize(memory_get_usage()));
        } else {
            $this->addParam('memory_usage', '');
        }

        $elapsed = microtime(true) - $this->elapsed_time;
        $this->addParam('elapsed_time', round($elapsed, 4));

        $params = array();
        $values = array();
        foreach ($this->params as $param => $value) {
            $params[] = $param;
            $values[] = $value;
        }

        $return = str_replace($params, $values, $this->output);
        return $return;
    }

}

?>
