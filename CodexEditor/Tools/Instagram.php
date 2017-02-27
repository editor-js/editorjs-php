<?php

namespace CodexEditor\Tools;

use CodexEditor\Factory;
use \CodexEditor\Tools\Base;

class Instagram extends Base {

    protected $template = 'instagram';

    public function initialize()
    {
        return $this->validate;
    }

    public function sanitize()
    {
    }

    public function validate()
    {
        $validType = is_array($this->data) && in_array($this->data['type'], Factory::getAllowedBlockTypes()['Instagram']);
        $urlNotEmpty = is_array($this->data['data']) && isset($this->data['data']['instagram_url']) && !empty($this->data['data']['instagram_url']);

        if ($validType && $urlNotEmpty)
            return true;

        return null;
    }

}