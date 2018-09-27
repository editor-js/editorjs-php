<?php

namespace CodexEditor\Tools;

use CodexEditor\Factory;
use \CodexEditor\Tools\Base;
use \CodexEditor\Interfaces\HTMLPurifyable;
use \HTMLPurifier;

class Lists extends Base implements HTMLPurifyable{

    public function initialize()
    {
        $this->sanitize();
        return $this->validate();
    }

    public function sanitize()
    {
        $allowedTags = 'a[href],br,p,strong,b,i,em';

        $sanitizer = clone $this->sanitizer;
        $sanitizer->set('HTML.Allowed', $allowedTags);

        $purifier = new HTMLPurifier($sanitizer);

        foreach ($this->data['data']['items'] as $key => $item) {
            $this->data['data']['items'][$key] = $purifier->purify($item);
        }
    }

    public function validate()
    {
        $validType      = is_array($this->data) && in_array($this->data['type'], Factory::getAllowedBlockTypes()['Paragraph']);
        $itemsNotEmpty  = true;

        foreach ($this->data['data']['items'] as $item) {
            $itemsNotEmpty = $itemsNotEmpty && !empty($items);
        }

        if ($validType && $itemsNotEmpty) {
            return true;
        }

        return null;
    }

}

