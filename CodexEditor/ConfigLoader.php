<?php

namespace CodexEditor;

/**
 * Class ConfigLoader
 *
 * @package CodexEditor
 */
class ConfigLoader
{
    public $tools = array();

    /**
     * ConfigLoader constructor.
     *
     * @param string $config_file
     *
     * @throws \Exception
     */
    public function __construct($config_file)
    {
        if (empty($config_file)) {
            throw new \Exception("Configuration filename is empty");
        }

        if (!file_exists($config_file)) {
            throw new \Exception("Configuration file not found");
        }

        $content = file_get_contents($config_file);

        if (empty($content)) {
            throw new \Exception("Configuration file is empty");
        }

        $config = json_decode($content, true);
        $this->loadTools($config);
    }

    /**
     * @param array $config
     *
     * @throws \Exception
     */
    private function loadTools($config)
    {
        if (!isset($config['tools'])) {
            throw new \Exception('Tools not found in configuration file');
        }

        foreach ($config['tools'] as $toolName => $toolData) {
            if (isset($this->tools[$toolName])) {
                throw new \Exception("Duplicate tool $toolName in configuration file");
            }

            $this->tools[$toolName] = $this->loadTool($toolData);
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function loadTool($data)
    {
        return $data;
    }
}