<?php

namespace CodexEditor\Tools;

use CodexEditor\Factory;
use \CodexEditor\Tools\Base;
use \CodexEditor\Interfaces\HTMLPurifyable;
use \HTMLPurifier;

class Link extends Base implements HTMLPurifyable {

    protected $template = 'link';

    /**
     * Fields in $this->data object
     * @var array
     */
    private $requredFields = array(
        'title'  => '',
        'description'  => 'a[href],p,strong,b,i,em',
        'linkText'   => '',
        'linkUrl' => ''
    );

    public function initialize()
    {
        $this->sanitize();
        return $this->validate();
    }

    public function sanitize()
    {
        if ($this->data['data']['style'] != 'smallCover' || $this->data['data']['style'] != 'bigCover') {
            $this->data['data']['style'] = 'smallCover';
        }

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

    public function validate()
    {
        $validType      = is_array($this->data) && in_array($this->data['type'], Factory::getAllowedBlockTypes()['Link']);
        $textNotEmpty   = !empty($this->data['data']['linkUrl']);

        if ($validType && $textNotEmpty) {

            return true;
        }

        return null;
    }

}