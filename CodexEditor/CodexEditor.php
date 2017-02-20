<?php

namespace CodexEditor;

use \CodexEditor\Factory;

/**
 * Class Structure
 * This class works with entry
 * Can :
 *  [] return an Array of decoded blocks after proccess
 *  [] return JSON encoded string
 *
 * @package CodexEditor
 */
class CodexEditor {

    /**
     * @var array - blocks classes
     */
    public $blocks = [];

    /**
     * Splits JSON string to separate blocks
     * @throws \Exception
     */
    public function __construct($json)
    {
        /**
         * Check input data
         */
        try {

            $data = json_decode($json, true);

        } catch ( \Exception $e ) {

            throw new \Exception('Wrong JSON format');

        }

        if (is_null($data) || count($data) === 0 || !isset($data['data']) || count($data['data']) === 0) {

            throw new \Exception('Input data is empty');
        }

        foreach ($data['data'] as $blockData) {

            if (is_array($blockData)) {

                    array_push($this->blocks, Factory::getBlock($blockData));

            } else {

                throw new \Exception('Error with block: %s', $blockData);

            }
        }

    }

    /**
     * Returns entry blocks as separate array element
     *
     * @return array
     */
    public function getBlocks()
    {
        /**
         * $callback {Function} Closure
         */
        $callback = function($block) {

            if ($block) {
                return $block->getData();
            }

        };

        return array_map( $callback, $this->blocks);

    }

    /**
     * @return {String} - json string of blocks
     */
    public function getData()
    {
        $this->makeIndexes();

        $callback = function($block) {
            return $block->getData();
        };

        return json_encode(['data' => array_map($callback, $this->blocks)], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Make indexed blocks
     */
    protected function makeIndexes()
    {
        $this->blocks = array_combine(range(0, count($this->blocks)-1), array_values($this->blocks));
    }

}