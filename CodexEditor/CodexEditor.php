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
     * @param mixed  $configuration
     *
     * @throws CodexEditorException()
     */
    public function __construct($json, $configuration)
    {
        $this->initPurifier();
        $this->handler = new BlockHandler($configuration, $this->sanitizer);

        /**
         * Check if json string is empty
         */
        if (empty($json)) {
            throw new CodexEditorException('JSON is empty');
        }

        /**
         * Check input data
         */
        $data = json_decode($json, true);

        /**
         * Handle decoding JSON error
         */
        if (json_last_error()) {
            throw new CodexEditorException('Wrong JSON format: ' . json_last_error_msg());
        }

        /**
         * Check if data is null
         */
        if (is_null($data)) {
            throw new CodexEditorException('Input is null');
        }

        /**
         * Count elements in data array
         */
        if (count($data) === 0) {
            throw new CodexEditorException('Input array is empty');
        }

        /**
         * Check if blocks param is missing in data
         */
        if (!isset($data['blocks'])) {
            throw new CodexEditorException('Field `blocks` is missing');
        }


        if (!is_array($data['blocks'])) {
            throw new CodexEditorException('Blocks is not an array');
        }

        foreach ($data['blocks'] as $blockData) {
            if (is_array($blockData)) {
                array_push($this->blocks, $blockData);
            } else {
                throw new CodexEditorException('Block must be an Array');
            }
        }

        /**
         * Validate blocks structure
         */
        $this->validateBlocks();
    }

    /**
     * Initialize HTML Purifier with default settings
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
     * Sanitize and return array of blocks according to the Handler's rules.
     *
     * @return array
     */
    public function getBlocks()
    {
        $sanitizedBlocks = [];

        foreach ($this->blocks as $block) {
            $sanitizedBlock = $this->handler->sanitizeBlock($block['type'], $block['data']);
            if (!empty($sanitizedBlock)) {
                array_push($sanitizedBlocks, $sanitizedBlock);
            }
        }

        return $sanitizedBlocks;
    }

    /**
     * Validate blocks structure according to the Handler's rules.
     *
     * @return bool
     */
    private function validateBlocks()
    {
        foreach ($this->blocks as $block) {
            if (!$this->handler->validateBlock($block['type'], $block['data'])) {
                return false;
            }
        }

        return true;
    }
}
