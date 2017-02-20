<?php

namespace CodexEditor\Tools;

use \CodexEditor\Tools\Base;
use \CodexEditor\Interfaces\HTMLPurifyable;
use \HTMLPurifier;

class Embed extends Base implements HTMLPurifyable {

    protected $template = 'embed';

    public function initialize()
    {
        // TODO: Implement initialize() method.
    }

    public function sanitize()
    {
        // TODO: Implement sanitize() method.
    }

    public function validate()
    {
        // TODO: VALIDATE
    }
}