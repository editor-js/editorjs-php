<?php

namespace CodexEditor\Tools;

use \CodexEditor\Factory;
use \CodexEditor\Tools\Base;
use \CodexEditor\Interfaces\HTMLPurifyable;
use \HTMLPurifier;

class Personality extends Base implements HTMLPurifyable {

    protected $template = 'personality';

    /**
     * Fields in $this->data object
     * @var array
     */
    private $requredFields = array(
        'name'  => '',
        'cite'  => 'a[href],p,strong,b,i,em',
        'url'   => '',
        'photo' => ''
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
        foreach ($this->requredFields as $field => $allowedTags) {

            $sanitizer = clone $this->sanitizer;
            $purifier  = new HTMLPurifier($sanitizer);

            if ($allowedTags) {
                $sanitizer->set('HTML.Allowed', $allowedTags);
                $sanitizer->set('AutoFormat.RemoveEmpty', true);
            }

            $this->data['data'][$field] = $purifier->purify($this->data['data'][$field]);
            $this->data['data'][$field] = trim($this->data['data'][$field]);

        }

    }

    /**
     * Validate input data
     *
     * @return boolean
     */
    public function validate()
    {
        $validType      = is_array($this->data) && in_array($this->data['type'], Factory::getAllowedBlockTypes()['Personality']);
        $nameNotEmpty   = !empty($this->data['data']['name']);

        if ($validType && $nameNotEmpty) {

            return true;
        }

        return null;
    }

    /**
     * Must return HTML template
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

}