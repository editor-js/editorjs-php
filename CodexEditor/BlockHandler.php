<?php

namespace CodexEditor;

/**
 * Class BlockHandler
 *
 * @package CodexEditor
 */
class BlockHandler
{
    /**
     * Default pseudo-key for numerical arrays
     */
    const DEFAULT_ARRAY_KEY = "-";

    /**
     * @var ConfigLoader|null
     */
    private $rules = null;

    /**
     * @var \HTMLPurifier_Config
     */
    private $sanitizer;

    /**
     * BlockHandler constructor
     *
     * @param \HTMLPurifier_Config $sanitizer
     * @param string               $configuration
     *
     * @throws CodexEditorException
     */
    public function __construct($configuration, $sanitizer)
    {
        $this->rules = new ConfigLoader($configuration);
        $this->sanitizer = $sanitizer;
    }

    /**
     * Validate block for correctness
     *
     * @param string $blockType
     * @param array  $blockData
     *
     * @throws CodexEditorException
     *
     * @return bool
     */
    public function validateBlock($blockType, $blockData)
    {
        /**
         * Default action for blocks that are not mentioned in a configuration
         */
        if (!array_key_exists($blockType, $this->rules->tools)) {
            throw new CodexEditorException("Tool `$blockType` not found in the configuration");
        }

        $rule = $this->rules->tools[$blockType];

        return $this->validate($rule, $blockData);
    }

    /**
     * Apply sanitizing rules according to the block type
     *
     * @param string $blockType
     * @param array  $blockData
     *
     * @throws CodexEditorException
     *
     * @return array|bool
     */
    public function sanitizeBlock($blockType, $blockData)
    {
        $rule = $this->rules->tools[$blockType];

        return [
            'type' => $blockType,
            'data' => $this->sanitize($rule, $blockData)
        ];
    }

    /**
     * Apply validation rule to the data block
     *
     * @param array $rules
     * @param array $blockData
     *
     * @throws CodexEditorException
     *
     * @return bool
     */
    private function validate($rules, $blockData)
    {
        /**
         * Make sure that every required param exists in data block
         */
        foreach ($rules as $key => $value) {
            if (($key != BlockHandler::DEFAULT_ARRAY_KEY) && (isset($value['required']) ? $value['required'] : true)) {
                if (!isset($blockData[$key])) {
                    throw new CodexEditorException("Not found required param `$key`");
                }
            }
        }

        /**
         * Check if there is not extra params (not mentioned in configuration rule)
         */
        foreach ($blockData as $key => $value) {
            if (!is_integer($key) && !isset($rules[$key])) {
                throw new CodexEditorException("Found extra param `$key`");
            }
        }

        /**
         * Validate every key in data block
         */
        foreach ($blockData as $key => $value) {
            /**
             * PHP Array has integer keys
             */
            if (is_integer($key)) {
                $key = BlockHandler::DEFAULT_ARRAY_KEY;
            }

            $rule = $rules[$key];
            $elementType = $rule['type'];

            /**
             * Process canBeOnly rule
             */
            if (isset($rule['canBeOnly'])) {
                if (!in_array($value, $rule['canBeOnly'])) {
                    throw new CodexEditorException("Option '$key' with value `$value` has invalid value. Check canBeOnly param.");
                }
            }

            /**
             * Validate element types
             */
            switch ($elementType) {
                case 'string':
                    break;

                case 'integer':
                case 'int':
                    if (!is_integer($value)) {
                        throw new CodexEditorException("`$value` is not an integer");
                    }
                    break;

                case 'array':
                    $this->validate($rule['data'], $value);
                    break;

                case 'boolean':
                case 'bool':
                    $blockData[$key] = boolval($value);
                    break;

                default:
                    throw new CodexEditorException("Unhandled type `$elementType`");
            }
        }

        return true;
    }

    /**
     * Sanitize strings in the data block
     *
     * @param array $rules
     * @param array $blockData
     *
     * @throws CodexEditorException
     *
     * @return array
     */
    private function sanitize($rules, $blockData)
    {
        /**
         * Sanitize every key in data block
         */
        foreach ($blockData as $key => $value) {
            $rule = $rules[$key];
            $elementType = $rule['type'];

            /**
             * Sanitize string with Purifier
             */
            if ($elementType == 'string') {
                $allowedTags = isset($rule['allowedTags']) ? $rule['allowedTags'] : '';
                $blockData[$key] = $this->getPurifier($allowedTags)->purify($value);
            }

            /**
             * Sanitize nested elements
             */
            if ($elementType == 'array') {
                $blockData[$key] = $this->sanitize($rule['data'], $value);
            }
        }

        return $blockData;
    }

    /**
     * Create and return new default purifier
     *
     * @param $allowedTags
     *
     * @return \HTMLPurifier
     */
    private function getPurifier($allowedTags)
    {
        $sanitizer = clone $this->sanitizer;
        $sanitizer->set('HTML.Allowed', $allowedTags);

        $purifier = new \HTMLPurifier($sanitizer);

        return $purifier;
    }
}
