<?php

namespace CodexEditor\Tools;

use \CodexEditor\Factory;
use \CodexEditor\Tools\Base;

class Code extends Base {

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
        $validType = is_array($this->data) && in_array($this->data['type'], Factory::getAllowedBlockTypes()['Code']);
        $textNotEmpty = is_array($this->data['data']) && isset($this->data['data']['text']) && !empty($this->data['data']['text']);

        if ( $validType && $textNotEmpty)
            return true;

        return null;
    }

}