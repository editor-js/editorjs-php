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

    /** error with json */
    const ERROR_WITH_JSON = 'Ошибка при обработке входных данных';

    /** error with input data */
    const ERROR_WITH_DATA = 'Некорректные данные';

    /** error with block processing */
    const ERROR_WITH_BLOCKS = 'Ошибка обработки блока';

    /**
     * @var array - blocks classes
     */
    public $blocks = [];

    /**
     * @var array - errors
     */
    public $errors = [];

    /**
     * Splits json string to separate blocks
     */
    public function __construct($json)
    {
        /**
         * Check input data
         */
        try {

            $data = json_decode($json, true);

        } catch ( \Exception $e ) {

            $this->errors[] = array(
                self::ERROR_WITH_JSON => $e->getMessage()
            );

        }

        if (is_null($data) || count($data) === 0 || !isset($data['data']) || count($data['data']) === 0) {

            $this->errors[] = array(
                self::ERROR_WITH_DATA => 'Массив пустой'
            );
        }

        /** Errors found */
        if (is_null($this->errors)) {
            return $this->errors;
        }

        foreach ($data['data'] as $blockData) {

            if (is_array($blockData)) {

                try {

                    array_push($this->blocks, Factory::getBlock($blockData));

                } catch ( \Exception $e) {

                    $this->errors[] = array(
                        self::ERROR_WITH_BLOCKS => $e->getMessage()
                    );
                }
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