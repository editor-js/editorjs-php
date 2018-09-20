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
class BlockHandler
{
    private $rules = null;
    private $sanitizer;

    public function __construct($configuration_filename, $sanitizer)
    {
        $this->rules = new ConfigLoader($configuration_filename);
        $this->sanitizer = $sanitizer;
    }

    public function validate_block($blockType, $blockData)
    {
        /**
         * Default action for blocks that are not mentioned in a configuration
         */
        if (!array_key_exists($blockType, $this->rules->tools)) {
            return true;
        }

        $rule = $this->rules->tools[$blockType];

//        echo "\n$blockType\n=========";
        return [
            'type' => $blockType,
            'data' => $this->validate($rule, $blockData)
        ];
    }

    public function validate($rule, $blockData) {
        foreach ($rule as $key => $value) {
            /**
             * Check if required params are presented in data
             */
            if (($key != "-") && ($value['required'] ?? true)) {
                if (!isset($blockData[$key])) {
                    throw new \Exception("Not found required param $key");
                }
            }
        }

        foreach ($blockData as $key => $value) {
            /**
             * Check if there is not extra params
             */
            if (!is_integer($key) && !isset($rule[$key])) {
                throw new \Exception("Found extra param $key");
            }
        }

        foreach ($blockData as $key => $value) {
            if (is_integer($key)) {
                $key = "-";
            }
            $elementType = $rule[$key]['type'];
//            echo "\nProcessing: $key ($elementType)";

            if ($elementType == 'const') {
                if (!in_array($value, $rule[$key]['canBeOnly'])) {
                    throw new \Exception("$value const is invalid");
                }
            }
            else if ($elementType == 'string') {
                $allowedTags = $rule[$key]['allowedTags'] ?? '';
                $blockData[$key] = $this->getPurifier($allowedTags)->purify($value);
            }
            else if ($elementType == 'int') {
                if (!is_integer($value)) {
                    throw new \Exception("$value is not integer");
                }
            }
            else if ($elementType == 'array') {
                $blockData[$key] = $this->validate($rule[$key]['data'], $value);
            }
            else {
                throw new \Exception("Unhandled type: $elementType");
            }
        }
//        echo "\n";
        return $blockData;
    }

    private function getPurifier($allowedTags) {
        $sanitizer = clone $this->sanitizer;
        $sanitizer->set('HTML.Allowed', $allowedTags);
        $purifier = new \HTMLPurifier($sanitizer);
        return $purifier;
    }

    private static function get($key, $default=null) {
        return isset($key) ? $key : $default;
    }
}