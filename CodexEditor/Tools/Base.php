<?php

namespace CodexEditor\Tools;

use \HTMLPurifier;
use \CodexEditor\Interfaces\Tools;
use \CodexEditor\Interfaces\HTMLPurifyable;

/**
 * Abstract class Base
 * may be used as interface of Tools of Codex.Editor
 * Each Tool must implement this class and define abstract methods
 *
 * @author Khaydarov Murod
 * @author Khaydarov Murod <murod.haydarov@gmail.com>
 * @copyright 2017 Codex Team
 * @license MIT
 *
 * @package CodexEditor\Blocks
 * @var string $data - input json as string
 * @var object $sanitizer - html purifier
 * @var string $template - path to html template of tool
 *
 */

abstract class Base implements Tools {

    /**
     * @var $data {Array} - Block data
     */
    protected $data;

    /**
     * @var $sanitizer {Object} - Purifier
     */
    protected $sanitizer;

    /**
     * @var $template {HTML} - HTML content
     */
    protected $template;

    /**
     * Base constructor.
     * @param $data
     */
    public function __construct($data)
    {

        $this->data = $data;

        if ($this instanceof HTMLPurifyable) {

            $this->sanitizer = \HTMLPurifier_Config::createDefault();

            $this->sanitizer->set('HTML.TargetBlank', true);
            $this->sanitizer->set('URI.AllowedSchemes', ['http' => true, 'https' => true]);
            $this->sanitizer->set('AutoFormat.RemoveEmpty', true);
            $this->sanitizer->set('Cache.SerializerPath', '/tmp/purifier');
        }

    }

    /** Initialize Block */
    abstract function initialize();

    /** Should be extended by Block Class */
    abstract function validate();

    /** Should be extended by Block Class */
    abstract function sanitize();

    public function getData()
    {
        return $this->data;
    }

    public static function getAllowedBlockTypes()
    {
        return include('../Config/BlockTypes.php');
    }


}