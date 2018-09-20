<?php

namespace CodexEditor;

/**
 * Class Structure
 * This class works with entry
 * Can :
 *  [] return an Array of decoded blocks after proccess
 *  [] return JSON encoded string
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
     * @var $handler
     */
    public $handler;

    /**
     * @var $handler
     */
    public $sanitizer;


    /**
     * Splits JSON string to separate blocks
     * @throws \Exception
     */
    public function __construct($json, $configuration_filename)
    {
        $this->initPurifier();
        $this->handler = new BlockHandler($configuration_filename, $this->sanitizer);

        /**
         * Check input data
         */
        $data = json_decode($json, true);

        if (json_last_error()) {
            throw new \Exception('Wrong JSON format: ' . json_last_error_msg());
        }

        if (is_null($data)) {
            throw new \Exception('Input is null');
        }

        if (count($data) === 0) {
            throw new \Exception('Input array is empty');
        }

        if (!isset($data['blocks'])) {
            throw new \Exception('Items missed');
        }

        if (count($data['blocks']) === 0) {
            throw new \Exception('Input blocks are empty');
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

    private function initPurifier() {
        $this->sanitizer = \HTMLPurifier_Config::createDefault();

        $this->sanitizer->set('HTML.TargetBlank', true);
        $this->sanitizer->set('URI.AllowedSchemes', ['http' => true, 'https' => true]);
        $this->sanitizer->set('AutoFormat.RemoveEmpty', true);

        if (!is_dir('/tmp/purifier')) {
            mkdir('/tmp/purifier', 0777, true);
        }

        $this->sanitizer->set('Cache.SerializerPath', '/tmp/purifier');
    }

    public function sanitize() {
        $sanitizedBlocks = [];
        foreach ($this->blocks as $block) {
            array_push($sanitizedBlocks, $this->handler->validate_block($block['type'], $block['data']));
        }
        return $sanitizedBlocks;
    }
}