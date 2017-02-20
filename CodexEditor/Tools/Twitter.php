<?php

namespace CodexEditor\Tools;

use \CodexEditor\Tools\Base;
use \CodexEditor\Interfaces\HTMLPurifyable;
use \HTMLPurifier;

class Twitter extends Base implements HTMLPurifyable {

    protected $template = 'twitter';

    public function initialize()
    {
        $this->sanitize();
        return $this->validate();
    }

    public function sanitize()
    {
        // TODO: Implement sanitize() method.
    }

    public function validate()
    {
        
    }
}