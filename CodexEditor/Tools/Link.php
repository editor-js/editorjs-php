<?php

namespace CodexEditor\Tools;

use \CodexEditor\Tools\Base;

class Link extends Base {

    protected $template = 'link';

    public function initialize()
    {
        return $this->validate();
    }

    public function sanitize()
    {
    }

    public function validate()
    {
        return true;
    }

}