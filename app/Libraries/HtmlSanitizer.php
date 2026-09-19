<?php

namespace App\Libraries;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * This classes is responsible for sanitizing HTML rich text editors
 */
class HtmlSanitizer
{
    private HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Allowed', 'p,b,strong,i,em,u,a[href|title],img[src|alt|style|width|height],ul,ol,li,br,div[style],span[style]');
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'data' => true]); 

        $this->purifier = new HTMLPurifier($config);
    }

    public function purify(string $dirtyHtml): string
    {
        return $this->purifier->purify($dirtyHtml);
    }
}
