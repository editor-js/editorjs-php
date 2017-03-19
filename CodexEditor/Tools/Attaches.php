<?php

namespace CodexEditor\Tools;

use CodexEditor\Factory;
use \CodexEditor\Tools\Base;
use \CodexEditor\Interfaces\HTMLPurifyable;
use \HTMLPurifier;

class Attaches extends Base implements HTMLPurifyable {

    protected $template = 'attaches';

    public function initialize()
    {
        return true;
    }

    /**
     * Clear dirty data
     *
     * @return void
     */
    public function sanitize()
    {


    }

    /**
     * Validate input data
     *
     * @return boolean
     */
    public function validate()
    {

    }

    /**
     * Must return HTML template
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

}