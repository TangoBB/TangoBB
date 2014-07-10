<?php

/*
 * Additional Parsing Library of TangoBB
 * Rewritten by Jaques1 (https://github.com/TangoBB/TangoBB/pull/6)
 */

if( !defined('BASEPATH') ){ die(); }

class Library_Parse {

    private $custom_codes = array();

    /*
     * Adding a BBCode
     */
    public function addCode($search, $replace) {
      $this->custom_codes[$search] = $replace;
    }

    /*
     * Parsing BBCode into HTML.
     */
    public function parse($string) {
        // closures can't access $this directly until PHP 5.4
        $this_object = $this;

        $string      = str_replace(
            array(
                '<',
                '>'
            ),
            array(
                '&lt;',
                '&gt;'
            ),
            $string
        );

        $tags = array(
            // bold
            '#\\[b\\](.*?)\\[/b\\]#uis' => '<b>\\1</b>',
            // italic
            '#\\[i\\](.*?)\\[/i\\]#uis' => '<i>\\1</i>',
            // underlined
            '#\\[u\\](.*?)\\[/u\\]#uis' => '<u>\\1</u>',
            // strikethrough
            '#\\[s\\](.*?)\\[/s\\]#uis' => '<del>\\1</del>',
            // aligned left
            '#\\[left\\](.*?)\\[/left\\]#uis' => '<p align="left">\\1</p>',
            // centered
            '#\\[center\\](.*?)\\[/center\\]#uis' => '<p align="center">\\1</p>',
            // aligned right
            '#\\[right\\](.*?)\\[/right\\]#uis' => '<p align="right">\\1</p>',
            // font size
            '#\\[size=([^\\]]*?)\\](.*?)\\[/size\\]#uis' => '<font size="\\1">\\2</font>',
            // font color
            '#\\[color=([^\\]]*?)\\](.*?)\\[/color\\]#uis' => '<font color="\\1">\\2</font>',
            // font face
            '#\\[font=([^\\]]*?)\\](.*?)\\[/font\\]#uis' => '<font face="\\1">\\2</font>',
            // preformatted
            '#\\[code\\](.*?)\\[/code\\]#uis' => '<pre>\\1</pre>',
            // image
            '#\\[img\\](.*?)\\[/img\\]#uis' => function ($matches) {
                $output = '';

                $url = trim($matches[1]);
                if ($this->checkSafeUrl($url)) {
                    $output = '<img src="' . $url . '" alt="user-provided image">';
                }

                return $output;
            },
            // link without custom text
            '#\\[url\\](.*?)\\[/url\\]#uis' => function ($matches) use ($this_object) {
                $output = '';

                $url = trim($matches[1]);
                if ($this_object->checkSafeUrl($url)) {
                    $output = '<a href="' . $url . '" rel="nofollow">' . $url . '</a>';
                }

                return $output;
            },
            // link with custom text
            '#\\[url=([^\\]]*?)\\](.*?)\\[/url\\]#uis' => function ($matches) use ($this_object) {
                $output = '';

                $url = trim($matches[1]);
                $text = $matches[2];
                if ($this_object->checkSafeUrl($url)) {
                    $output = '<a href="' . $url . '" rel="nofollow">' . $text . '</a>';
                }

                return $output;
            },
            // unordered list
            '#\\[ul\\](.*?)\[/ul\\]#uis' => function ($matches) use ($this_object) {
                return '<ul>' . trim($this_object->parseListElements($matches[1])) . '</ul>';
            },
            // unordered list (alternative syntax)
            '#\\[list\\](.*?)\[/list\\]#uis' => function ($matches) use ($this_object) {
                return '<ul>' . trim($this_object->parseListElements($matches[1])) . '</ul>';
            },
            // ordered list
            '#\\[ol\\](.*?)\[/ol\\]#uis' => function ($matches) use ($this_object) {
                return '<ol>' . trim($this_object->parseListElements($matches[1])) . '</ol>';
            },
            // ordered list (alternative syntax)
            '#\\[list=1\\](.*?)\[/list\\]#uis' => function ($matches) use ($this_object) {
                return '<ol>' . trim($this_object->parseListElements($matches[1])) . '</ol>';
            },
            // quote
            '#\[quote\](.*?)\[/quote\]#uis' => function ($matches) use ($this_object) {
                return $this_object->parseQuote($matches[1]);
            },

        );

        // replace non-breaking spaces (caused by contenteditable attribute) with regular spaces
        $result = str_replace("\xc2\xa0", ' ', $string);

        //$result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');

        foreach ($tags as $pattern => $replacement) {
            if (is_callable($replacement)) {
                $result = preg_replace_callback($pattern, $replacement, $result);
            } else {
                if($pattern=='#\\[code\\](.*?)\\[/code\\]#uis')
                {
                    $result = preg_replace('/[\n\r]+/', '<br>',$result);
                }
                
                $result = preg_replace($pattern, $replacement, $result);
            }
        }

        $result = preg_replace(array_keys($this->custom_codes), array_values($this->custom_codes), $result);

        //Mentions
        $result = preg_replace('/@(\w+)/', '@<a href="' . SITE_URL . '/members.php/cmd/user/id/$1">$1</a>', $result);

        //Additional parsing that should be fixed.
        $result = str_replace(
            array(
                '[/*]',
                '%'
            ),
            array(
                '',
                '&#37;'
            ),
            $result
        );

        $result = nl2br($result);

        return $result;
    }

    /*
     * Check if a URL is valid and either uses the HTTP or the HTTPS scheme
     *
     * It's very important to not accept other schemes like `javascript:` or
     * `data:`, because they can be used for cross-site scripting.
     */
    public function checkSafeUrl($url) {
        $valid_url = false;

        if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED))
        {
            $parsed_url = parse_url($url);
            $valid_url = (boolean) preg_match('#\\Ahttps?\\z#ui', $parsed_url['scheme']);
        }

        return $valid_url;
    }

    public function parseListElements($list_content) {
        /*
         * TangoBB uses both the [li] tag and the [*] tag for list elements.
         * Allowing both variants at the same time would be confusing, though.
         * If [li] is used, we only parse those tags. Otherwise, we only parse
         * the [*] tags.
         */
        $uses_li_syntax = preg_match('#\\[/?li\\]#is', $list_content);

        $render_list_element = function ($match) {
            return '<li>' . trim($match[1]) . '</li>';
        };

        if ($uses_li_syntax) {
            $result = preg_replace_callback('#\\[li\\](.*?)\\[/li\\]\\s*#uis', $render_list_element, $list_content);
        } else {
            $result = preg_replace_callback('#\\[\\*\\](.*?)(?=\\[\\*\\]|\\z)#uis', $render_list_element, $list_content);
        }

        return $result;
    }

    /*
     * Additional parsing on forum content.
     */
    private function parseQuote($raw_id) {
        global $MYSQL, $TANGO;

        $id = preg_replace('#\\s+#u', '', $raw_id);
        $MYSQL->where('id', $id);
        $query = $MYSQL->get('{prefix}forum_posts');
        $user  = (!empty($query))? $TANGO->user($query['0']['post_user']) : array(
          'username' => ''
        );
        $q_c   = (!empty($query))? $query['0']['post_content'] : $raw_id;
        $quote = $TANGO->tpl->entity(
            'quote_post',
            array(
                'quoted_post_content',
                'quoted_post_user'
            ),
            array(
                $this->parse($this->removeQuote($q_c)),
                $user['username']
            )
        );

        return $quote;
    }

    private function removeQuote($string) {
        return preg_replace('#\\[quote\\](.*?)\\[/quote\\]#uis', '', $string);
    }

}