<?php

namespace CodexEditor;

/**
 * Class ConfigLoader
 *
 * @package CodexEditor
 */
class ConfigLoader
{
    public $tools = [];

    /**
     * ConfigLoader constructor
     *
     * @param string $configuration – configuration data
     *
     * @throws \Exception
     */
    public function __construct($configuration)
    {
        if (empty($configuration)) {
            throw new \Exception("Configuration data is empty");
        }

        $config = json_decode($configuration, true);
        $this->loadTools($config);
    }

    /**
     * Load settings for tools from configuration
     *
     * @param array $config
     *
     * @throws \Exception
     */
    private function loadTools($config)
    {
        if (!isset($config['tools'])) {
            throw new \Exception('Tools not found in configuration');
        }

        foreach ($config['tools'] as $toolName => $toolData) {
            if (isset($this->tools[$toolName])) {
                throw new \Exception("Duplicate tool $toolName in configuration");
            }

            $this->tools[$toolName] = $this->loadTool($toolData);
        }
    }

    /**
     * Load settings for tool
     *
     * @param array $data
     *
     * @return array
     */
    private function loadTool($data)
    {
        return $data;
    }
}
