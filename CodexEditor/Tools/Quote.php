<?php

namespace CodexEditor\Tools;

use \CodexEditor\Tools\Base;
use \CodexEditor\Interfaces\HTMLPurifyable;
use \HTMLPurifier;

class Quote extends Base implements HTMLPurifyable {

    protected $template = 'quote';

    public function initialize()
    {
        $this->sanitize();
        return $this->validate();
    }

    public function sanitize()
    {
        $allowedTags = 'a[href], p, br, strong, i, em';

        $sanitizer = clone $this->sanitizer;
        $sanitizer->set('HTML.Allowed', $sanitizer);

        $purifier = new HTMLPurifier($sanitizer);

        /**
         * @todo purify data
         */
    }

    public function validate()
    {
        
    }

}