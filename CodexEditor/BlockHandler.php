<?php

namespace CodexEditor;

/**
 * Class BlockHandler
 *
 * @package CodexEditor
 */
class BlockHandler
{
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
     * BlockHandler constructor.
     *
     * @param \HTMLPurifier_Config $sanitizer
     * @param mixed                $configuration
     *
     * @throws \Exception
     */
    public function __construct($configuration, $sanitizer)
    {
        $this->rules = new ConfigLoader($configuration);
        $this->sanitizer = $sanitizer;
    }

    /**
     * Validate block for correctness and apply sanitizing rules according to the block type
     *
     * @param $blockType
     * @param $blockData
     *
     * @throws \Exception
     *
     * @return array|bool
     */
    public function validate_block($blockType, $blockData)
    {
        /**
         * Default action for blocks that are not mentioned in a configuration
         */
        if (!array_key_exists($blockType, $this->rules->tools)) {
            return true;
        }

        $rule = $this->rules->tools[$blockType];

        return [
            'type' => $blockType,
            'data' => $this->validate($rule, $blockData)
        ];
    }

    /**
     * Apply validation rule to the data block
     *
     * @param $rules
     * @param $blockData
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function validate($rules, $blockData)
    {
        /**
         * Make sure that every required param exists in data block
         */
        foreach ($rules as $key => $value) {
            if (($key != BlockHandler::DEFAULT_ARRAY_KEY) && (isset($value['required']) ? $value['required'] : true)) {
                if (!isset($blockData[$key])) {
                    throw new \Exception("Not found required param `$key`");
                }
            }
        }

        /**
         * Check if there is not extra params (not mentioned in configuration rule)
         */
        foreach ($blockData as $key => $value) {
            if (!is_integer($key) && !isset($rules[$key])) {
                throw new \Exception("Found extra param `$key`");
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
                    throw new \Exception("`$value` has invalid value. Check canBeOnly param.");
                }
            }

            /**
             * Validate element types
             */
            switch ($elementType) {
                case 'string':
                    $allowedTags = isset($rule['allowedTags']) ? $rule['allowedTags'] : '';
                    $blockData[$key] = $this->getPurifier($allowedTags)->purify($value);
                    break;

                case 'integer':
                case 'int':
                    if (!is_integer($value)) {
                        throw new \Exception("`$value` is not an integer");
                    }
                    break;

                case 'array':
                    $blockData[$key] = $this->validate($rule['data'], $value);
                    break;

                case 'boolean':
                case 'bool':
                    $blockData[$key] = boolval($value);
                    break;

                default:
                    throw new \Exception("Unhandled type `$elementType`");
            }
        }

        return $blockData;
    }

    /**
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

    /**
     * @param      $key
     * @param null $default
     *
     * @return bool
     */
    private static function get($key, $default = null)
    {
        return isset($key) ? $key : $default;
    }
}
