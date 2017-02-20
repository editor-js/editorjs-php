<?php

namespace CodexEditor\Tools;

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
        if (is_array($this->data) && in_array($this->data['type'], self::getAllowedBlockTypes()['Instagram'])
            && is_array($this->data['data']) && isset($this->data['data']['instagram_url']) && !empty($this->data['data']['instagram_url']))

            return true;

        return null;
    }

}