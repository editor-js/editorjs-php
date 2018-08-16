<?php

namespace CodexEditor\Tools;

class Delimiter extends Base {

    protected $template = 'delimiter';

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