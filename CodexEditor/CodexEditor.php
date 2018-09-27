<?php

namespace CodexEditor;

/**
 * Class CodexEditor
 *
 * @package CodexEditor
 */
class CodexEditor
{
    /**
     * @var array $blocks - blocks classes
     */
    public $blocks = [];

    /**
     * @var array $config - list for block's classes
     */
    public $config;

    /**
     * @var BlockHandler
     */
    public $handler;

    /**
     * @var \HTMLPurifier_Config
     */
    public $sanitizer;

    /**
     * CodexEditor constructor.
     * Splits JSON string to separate blocks
     *
     * @param string $json
     * @param string $configuration_filename
     *
     * @throws \Exception
     */
    public function __construct($json, $configuration_filename)
    {
        $this->initPurifier();
        $this->handler = new BlockHandler($configuration_filename, $this->sanitizer);

        /**
         * Check if json string is empty
         */
        if (empty($json)) {
            throw new \Exception('JSON is empty');
        }

        /**
         * Check input data
         */
        $data = json_decode($json, true);

        /**
         * Handle decoding JSON error
         */
        if (json_last_error()) {
            throw new \Exception('Wrong JSON format: ' . json_last_error_msg());
        }

        /**
         * Check if data is null
         */
        if (is_null($data)) {
            throw new \Exception('Input is null');
        }

        /**
         * Count elements in data array
         */
        if (count($data) === 0) {
            throw new \Exception('Input array is empty');
        }

        /**
         * Check if blocks param is missing in data
         */
        if (!isset($data['blocks'])) {
            throw new \Exception('Field `blocks` is missing');
        }


        if (!is_array($data['blocks'])) {
            throw new \Exception('Blocks is not an array');
        }

        foreach ($data['blocks'] as $blockData) {
            if (is_array($blockData)) {
                array_push($this->blocks, $blockData);
            } else {
                throw new \Exception('Block must be an Array');
            }
        }
    }

    /**
     *
     */
    private function initPurifier()
    {
        $this->sanitizer = \HTMLPurifier_Config::createDefault();

        $this->sanitizer->set('HTML.TargetBlank', true);
        $this->sanitizer->set('URI.AllowedSchemes', ['http' => true, 'https' => true]);
        $this->sanitizer->set('AutoFormat.RemoveEmpty', true);

        if (!is_dir('/tmp/purifier')) {
            mkdir('/tmp/purifier', 0777, true);
        }

        $this->sanitizer->set('Cache.SerializerPath', '/tmp/purifier');
    }

    /**
     * @return array
     */
    public function sanitize()
    {
        $sanitizedBlocks = [];

        foreach ($this->blocks as $block) {
            array_push($sanitizedBlocks, $this->handler->validate_block($block['type'], $block['data']));
        }

        return $sanitizedBlocks;
    }
}
