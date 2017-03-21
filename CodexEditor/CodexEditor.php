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
     * @var array $blocks - blocks classes
     */
    public $blocks = [];

    /**
     * @var array $config - list for block's classes
     */
    public $config;

    /**
     * Splits JSON string to separate blocks
     * @throws \Exception
     */
    public function __construct($json, $config = null)
    {

        if (!isset($config)) {
            $this->config = $config;
        }

        /**
         * Check input data
         */
        $data = json_decode($json, true);

        if (json_last_error()) {
            throw new \Exception('Wrong JSON format: ' . json_last_error_msg());
        }

        if ( is_null($data) ){
            throw new \Exception('Input is null');
        }

        if ( count($data) === 0 ) {
            throw new \Exception('Input array is empty');
        }

        /**
         * @todo Remove 'data', save 'items'
         */
        if ( !isset($data['data']) && !isset($data['items']) ){
            throw new \Exception('Data or items missed ');
        }

        if ( count($data['data']) === 0 ) {
            throw new \Exception('Input blocks are empty');
        }

        foreach ($data['data'] as $blockData) {

            if (is_array($blockData)) {

                    array_push($this->blocks, Factory::getBlock($blockData, $config));

            } else {

                throw new \Exception('Block' . $blockData['type'] . 'must be an Array');

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
        $this->makeIndexes();

        /**
         * $callback {Function} Closure
         */
        $callback = function($block) {

            if (!empty($block)) {
                return $block->getData();
            }

        };

        return array_map( $callback, $this->blocks);

    }

    /**
     * Returns all blocks data
     * @param Boolean $escapeHTML pass TRUE to escape HTML entities
     * @return {String} - json string of blocks
     */
    public function getData($escapeHTML = false)
    {
        $this->makeIndexes();

        $blocks = array();

        foreach ($this->blocks as $block){
            if (!empty($block)){
                $blocks[] = $block->getData($escapeHTML);
            }
        }

        return json_encode(array('data' => $blocks), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Make indexed blocks
     */
    protected function makeIndexes()
    {
        $this->clearDirtyBlocks();
        $this->blocks = array_combine(range(0, count($this->blocks)-1), array_values($this->blocks));
    }

    /**
     * Clean NULL's
     */
    private function clearDirtyBlocks()
    {
        for($i = 0; $i < count($this->blocks); $i++) {

            if (empty($this->blocks[$i])) {
                unset($this->blocks[$i]);
            }
        }
    }

}