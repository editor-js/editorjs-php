<?php

namespace CodexEditor\Blocks;

class Structure {

    /**
     * @var array - blocks classes
     */
    public $blocks = [];

    /**
     * Splits json string to separate blocks
     *
     */
    public function __construct($json)
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            exit;
        }

        if (is_null($data) || count($data) === 0 || !isset($data['data']) || count($data['data']) === 0) {
            exit;
        }

        $blocks = [];

        foreach ($data['data'] as $blockData) {

            if (is_array($blockData)) {
                try {

                    array_push($blocks, Factory::getBlock($blockData));

                } catch (Exception $e) {

                    var_dump($e);
                }
            }
        }

        $this->blocks = $blocks;

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