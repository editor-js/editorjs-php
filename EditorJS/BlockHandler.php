<?php

namespace EditorJS;

/**
 * Class BlockHandler
 *
 * @package EditorJS
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
     * BlockHandler constructor
     *
     * @param string $configuration
     *
     * @throws EditorJSException
     */
    public function __construct($configuration)
    {
        $this->rules = new ConfigLoader($configuration);
    }

    /**
     * Validate block for correctness
     *
     * @param string $blockType
     * @param array  $blockData
     *
     * @throws EditorJSException
     *
     * @return bool
     */
    public function validateBlock($blockType, $blockData)
    {
        /**
         * Default action for blocks that are not mentioned in a configuration
         */
        if (!array_key_exists($blockType, $this->rules->tools)) {
            throw new EditorJSException("Tool `$blockType` not found in the configuration");
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
     * @throws EditorJSException
     *
     * @return array|bool
     */
    public function sanitizeBlock($blockType, $blockData, $blockTunes)
    {
        $rule = $this->rules->tools[$blockType];

        return [
            'type' => $blockType,
            'data' => $this->sanitize($rule, $blockData),
            'tunes' => $blockTunes
        ];
    }

    /**
     * Apply validation rule to the data block
     *
     * @param array $rules
     * @param array $blockData
     *
     * @throws EditorJSException
     *
     * @return bool
     */
    private function validate($rules, $blockData)
    {
        return true;
    }

    /**
     * Sanitize strings in the data block
     *
     * @param array $rules
     * @param array $blockData
     *
     * @throws EditorJSException
     *
     * @return array
     */
    private function sanitize($rules, $blockData)
    {
        /**
         * Sanitize every key in data block
         */
        foreach ($blockData as $key => $value) {
            /**
             * PHP Array has integer keys
             */
            if (is_integer($key)) {
                $rule = $rules[BlockHandler::DEFAULT_ARRAY_KEY];
            } else {
                $rule = $rules[$key];
            }

            $rule = $this->expandToolSettings($rule);
            $elementType = $rule['type'];

            /**
             * Sanitize string with Purifier
             */
            if ($elementType == 'string') {
                $allowedTags = isset($rule['allowedTags']) ? $rule['allowedTags'] : '';
                if ($allowedTags !== '*') {
                    $blockData[$key] = $this->getPurifier($allowedTags)->purify($value);
                }
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
        $sanitizer = $this->getDefaultPurifier();

        $sanitizer->set('HTML.Allowed', $allowedTags);

        /**
         * Define custom HTML Definition for mark tool
         */
        if ($def = $sanitizer->maybeGetRawHTMLDefinition()) {
            $def->addElement('mark', 'Inline', 'Inline', 'Common');
        }

        $purifier = new \HTMLPurifier($sanitizer);

        return $purifier;
    }

    /**
     * Initialize HTML Purifier with default settings
     */
    private function getDefaultPurifier()
    {
        $sanitizer = \HTMLPurifier_Config::createDefault();

        $sanitizer->set('HTML.TargetBlank', true);
        $sanitizer->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true, 'tel' => true]);
        $sanitizer->set('AutoFormat.RemoveEmpty', true);
        $sanitizer->set('HTML.DefinitionID', 'html5-definitions');

        return $sanitizer;
    }

    /**
     * Check whether the array is associative or sequential
     *
     * @param array $arr – array to check
     *
     * @return bool – true if the array is associative
     */
    private function isAssoc(array $arr)
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Expand shortified tool settings
     *
     * @param $rule – tool settings
     *
     * @throws EditorJSException
     *
     * @return array – expanded tool settings
     */
    private function expandToolSettings($rule)
    {
        if (is_string($rule)) {
            // 'blockName': 'string' – tool with string type and default settings
            $expandedRule = ["type" => $rule];
        } elseif (is_array($rule)) {
            if ($this->isAssoc($rule)) {
                $expandedRule = $rule;
            } else {
                // 'blockName': [] – tool with canBeOnly and default settings
                $expandedRule = ["type" => "string", "canBeOnly" => $rule];
            }
        } else {
            throw new EditorJSException("Cannot determine element type of the rule `$rule`.");
        }

        return $expandedRule;
    }
}
