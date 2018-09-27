<?php

namespace CodexEditor\Tools;

use \CodexEditor\Factory;
use \CodexEditor\Tools\Base;

class Raw extends Base {

    protected $template = 'raw';

     /**
     * Fields in $this->data object
     * @var array
     */
    private $requredFields = array(
        'raw',
    );

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
        if (!is_array($this->data)) {
            return false;
        }

        if (!in_array($this->data['type'], Factory::getAllowedBlockTypes()['Raw']) ){
            return false;
        }

        foreach ($this->requredFields as $field) {
            if (empty($this->data['data'][$field])) {
                return false;
            }
        }

    }

}