<?php

namespace CodexEditor\Tools;

use CodexEditor\Factory;
use \CodexEditor\Tools\Base;
use \CodexEditor\Interfaces\HTMLPurifyable;
use \HTMLPurifier;

class Attaches extends Base implements HTMLPurifyable {

    protected $template = 'attaches';

    /**
     * Fields in $this->data object
     * @var array
     */
    private $requredFields = array(
        'name',
        'title',
        'size',
        'extension',
        'url'
    );

    public function initialize()
    {
        $this->sanitize();
        return $this->validate();
    }

    /**
     * Clear dirty data
     *
     * @return void
     */
    public function sanitize()
    {
        $sanitizer = clone $this->sanitizer;

        $purifier = new HTMLPurifier($sanitizer);

        foreach ($this->requredFields as $field) {
            $this->data['data'][$field] = $purifier->purify($this->data['data'][$field]);
        }
    }

    /**
     * Validate input data
     *
     * @return boolean
     */
    public function validate()
    {
        if (!is_array($this->data)) {
            return false;
        }

        if (!in_array($this->data['type'], Factory::getAllowedBlockTypes()['Attaches']) ){
            return false;
        }

        foreach ($this->requredFields as $field) {
            if (empty($this->data['data'][$field])) {
                return false;
            }
        }

        return true;

    }

    /**
     * Returns block 'data'
     * @param Boolean $escapeHTML  pass TRUE to escape HTML entities
     * @return array    with block data
     */
    public function getData($escapeHTML = false)
    {
        foreach ($this->data['data'] as $key => $value) {
            if ($escapeHTML) {
                $this->data['data'][$key] = htmlspecialchars($value);
            } else {
                $this->data['data'][$key] = $value;
            }
        }

        return $this->data;
    }

    /**
     * Must return HTML template
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

}