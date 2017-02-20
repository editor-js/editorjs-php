<?php

namespace CodexEditor\Tools;

use \CodexEditor\Tools\Base;
use \HTMLPurifier;
use \CodexEditor\Interfaces\HTMLPurifyable;

class Image extends Base implements HTMLPurifyable {

    protected $template = 'image';

    public function initialize()
    {
        $this->sanitize();
        return $this->validate();
    }

    public function sanitize()
    {
        $allowedTags = 'b, a[href], strong, p, i, em';

        $sanitizer = clone $this->sanitizer;
        $sanitizer->set('HTML.Allowed', $allowedTags);

        $purifier = new HTMLPurifier($sanitizer);
        $this->data['data']['caption'] = $purifier->purify($this->data['data']['caption']);
    }

    /**
     * @todo VALIDATION
     */
    public function validate()
    {

    }

}