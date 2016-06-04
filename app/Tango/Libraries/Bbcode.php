<?php

namespace App\Tango\Libraries;

class Bbcode
{
    //Setting default BBCodes.
    private $codes = [
        'bold' => [
            'search' => '/\[b\](.*?)\[\/b\]/is',
            'replace' => '<strong>$1</strong>'
        ],
        'italic' => [
            'search' => '/\[i\](.*?)\[\/i\]/is',
            'replace' => '<em>$1</em>'
        ],
        'underline' => [
            'search' => '/\[u\](.*?)\[\/u\]/is',
            'replace' => '<u>$1</u>'
        ],
        'img' => [
            'search' => '/\[img\](.*?)\[\/img\]/is',
            'replace' => '<img src="$1" />'
        ],
        'link' => [
            'search' => '/\[url\=(.*?)\](.*?)\[\/url\]/is',
            'replace' => '<a href="$1">$2</a>'
        ],
        '' => [
            'search' => '/\[url\](.*?)\[\/url\]/is',
            'replace' => '<a href="$1">$1</a>'
        ]
    ];

    public function register($name = "", $search, $replace)
    {
        $codes[$name] = [
            'search' => $search,
            'replace' => $replace
        ];

        return $this;
    }

    public static function strip($string)
    {
        return preg_replace('#\[[^\]]+\]#', '', $string);
    }

    public function renderText($string)
    {
        $search  = [];
        $replace = [];

        foreach( $this->codes as $name => $action )
        {
            $search[]  = $action['search'];
            $replace[] = $action['replace'];
        }

        return htmlspecialchars(preg_replace($search, $replace, $string), ENT_NOQUOTES);
    }

    public function editorButtons()
    {
        $buttons = [];
        foreach($this->codes as $name => $action )
        {
            if( !empty($name) )
            {
                $buttons[] = $name;
            }
        }

        return implode(',', $buttons);
    }
}
