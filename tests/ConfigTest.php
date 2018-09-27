<?php

use CodexEditor\ConfigLoader;

class ConfigTest extends TestCase
{
    const SAMPLE_VALID_DATA = '{"time":1537444483710,"blocks":[{"type":"header","data":{"text":"CodeX Editor","level":2}},{"type":"paragraph","data":{"text":"Привет. Перед вами наш обновленный редактор. На этой странице вы можете проверить его в действии — попробуйте отредактировать или дополнить материал. Код страницы содержит пример подключения и простейшей настройки."}}],"version":"2.0.3"}';
    const EMPTY_DATA = '';

    public function testValidConfig()
    {
        $config = new ConfigLoader(file_get_contents(TESTS_DIR . "/samples/test-config.json"));
    }
}
