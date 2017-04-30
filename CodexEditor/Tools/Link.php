<?php

namespace CodexEditor\Tools;

use CodexEditor\Factory;
use \CodexEditor\Tools\Base;
use \CodexEditor\Interfaces\HTMLPurifyable;
use \HTMLPurifier;

class Link extends Base implements HTMLPurifyable {

    protected $template = 'link';

    public function initialize()
    {
        $this->sanitize();
        return $this->validate();
    }

    public function sanitize()
    {
        if ($this->data['data']['style'] != 'smallCover' || $this->data['data']['style'] != 'bigCover') {
            $this->data['data']['style'] = 'smallCover';
        }

        $allowedTags = 'a[href],br,p,b,i';

        $sanitizer = clone $this->sanitizer;
        $sanitizer->set('HTML.Allowed', $allowedTags);

        $purifier = new HTMLPurifier($sanitizer);
        $this->data['data']['title'] = $purifier->purify($this->data['data']['title']);
        $this->data['data']['description'] = $purifier->purify($this->data['data']['description']);
        $this->data['data']['linkText'] = $purifier->purify($this->data['data']['linkText']);
        $this->data['data']['linkUrl'] = $purifier->purify($this->data['data']['linkUrl']);

    }

    public function validate()
    {
        $validType      = is_array($this->data) && in_array($this->data['type'], Factory::getAllowedBlockTypes()['Link']);
        $textNotEmpty   = !empty($this->data['data']['linkUrl']);

        if ($validType && $textNotEmpty) {

            return true;
        }

        return null;
    }

}