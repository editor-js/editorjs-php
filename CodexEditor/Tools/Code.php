<?php

namespace CodexEditor\Tools;

use \CodexEditor\Tools\Base;

class Codex extends Base {

    protected $template = 'code';

    public function initialize()
    {
        $this->sanitize();
        return $this->validate();
    }

    /**
     * This plugin is not purifyable.
     * That's why it is not a instance of HTMLPurifyable
     */
    public function sanitize()
    {
    }

    public function validate()
    {
        if (is_array($this->data) && in_array($this->data['type'] == self::getAllowedBlockTypes()['Code'])
                && is_array($this->data['data']) && isset($this->data['data']['text']) && !empty($this->data['data']['text']))
            return true;

        return null;
    }

}