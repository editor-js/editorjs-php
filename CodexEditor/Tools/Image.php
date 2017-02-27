<?php

namespace CodexEditor\Tools;

use \CodexEditor\Factory;
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

    public function validate()
    {
        $validType = is_array($this->data) && in_array($this->data['type'], Factory::getAllowedBlockTypes()['Image']);
        $urlNotEmpty = !empty($this->data['data']['url']);

        if ($validType && $urlNotEmpty)
            return true;

        return null;
    }

}