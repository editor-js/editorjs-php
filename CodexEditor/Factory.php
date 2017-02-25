<?php

namespace CodexEditor;

use \CodexEditor\Tools\Base;

/**
 * Class Factory
 * This class will contain methods that will be used to handle/modify
 * basic classes
 *
 * @author Khaydarov Murod
 * @author Khaydarov Murod <murod.haydarov@gmail.com>
 * @copyright 2017 Codex Team
 * @license MIT
 * @package CodexEditor\Blocks
 *
 */
class Factory {

    /**
     * Get block class
     * @param array $data
     *
     * @return object
     */
    public static function getBlock(array $data, $config = null)
    {
        if (isset($data['type']) && !empty($data['type'])) {
            $type = ucfirst(trim(strtolower($data['type'])));

            /**
             * allowed datatypes from redactor
             */
            $allowedBlockNameTypes = $config ?: self::getAllowedBlockTypes();

            /**
             * Returns correct type name
             *
             * @var $blockName    - correct type name
             * @var $allowedTypes - list of allowed type names
             */
            foreach ($allowedBlockNameTypes as $blockName => $allowedTypes) {
                if (in_array($data['type'], $allowedTypes)) {
                    $type = $blockName;
                    break;
                }
            }

            /**
             * Getting block
             * @var $blockClass - Block Class
             */
            $blockClass = "CodexEditor\\Tools\\" . $type;

            if (class_exists($blockClass)) {

                /** Call Base Class constructor */
                $block = new $blockClass($data);

                /** Call implemented initialize method */
                $block->initialize();

                return $block;
            }
        }

        return null;
    }

    public static function getAllowedBlockTypes()
    {
        return require __DIR__ . '/' . 'Config/BlockTypes.php';
    }
}