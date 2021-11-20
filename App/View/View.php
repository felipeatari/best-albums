<?php

namespace App\View;

class View
{
    private static function loadView($page)
    {
        $view = 'public/pages/'.$page.'.html';
        return file_exists($view) ? file_get_contents($view) : '';
    }

    public static function render($page, $content = [])
    {
        $layout = self::loadView($page);
        $loadContent = '{{'.implode('}}&{{', array_keys($content)).'}}';
        return str_replace(explode('&', $loadContent), array_values($content), $layout);
    }
}