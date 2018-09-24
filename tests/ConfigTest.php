<?php

use CodexEditor\ConfigLoader;

class ConfigTest extends TestCase
{
    const SAMPLE_VALID_DATA = '{"time":1537444483710,"blocks":[{"type":"header","data":{"text":"CodeX Editor","level":2}},{"type":"paragraph","data":{"text":"Привет. Перед вами наш обновленный редактор. На этой странице вы можете проверить его в действии — попробуйте отредактировать или дополнить материал. Код страницы содержит пример подключения и простейшей настройки."}}],"version":"2.0.3"}';
    const EMPTY_DATA = '';

    public function testConfigFilenameEmpty()
    {
        $callable = function() {
            new ConfigLoader("");
        };

        $this->assertException($callable, Exception::class, null, 'Configuration filename is empty');
    }

    public function testConfigNotFound()
    {
        $callable = function() {
            new ConfigLoader("configuration.file");
        };

        $this->assertException($callable, Exception::class, null, 'Configuration file not found');
    }

    public function testConfigEmpty()
    {
        $callable = function() {
            new ConfigLoader(TESTS_DIR . "/samples/empty-config.json");
        };

        $this->assertException($callable, Exception::class, null, 'Configuration file is empty');
    }

    public function testValidConfig()
    {
        $config = new ConfigLoader(TESTS_DIR . "/samples/test-config.json");
    }

}