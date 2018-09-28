<?php

use CodexEditor\CodexEditor;
use CodexEditor\CodexEditorException;

/**
 * Class BlockHandlerTest
 *
 * Check block data sanitizing functionality
 */
class BlockHandlerTest extends TestCase
{
    const SAMPLE_VALID_DATA = '{"time":1537444483710,"blocks":[{"type":"header","data":{"text":"CodeX Editor","level":2}},{"type":"paragraph","data":{"text":"Привет. Перед вами наш обновленный редактор. На этой странице вы можете проверить его в действии — попробуйте отредактировать или дополнить материал. Код страницы содержит пример подключения и простейшей настройки."}}],"version":"2.0.3"}';

    public function testLoad()
    {
        new CodexEditor(BlockHandlerTest::SAMPLE_VALID_DATA, file_get_contents(TESTS_DIR . "/samples/test-config.json"));
    }

    public function testSanitizing()
    {
        $data = '{"blocks":[{"type":"header","data":{"text":"CodeX <b>Editor</b>","level":2}}]}';

        $editor = new CodexEditor($data, file_get_contents(TESTS_DIR . "/samples/test-config.json"));
        $result = $editor->sanitize();

        $this->assertEquals('CodeX Editor', $result[0]['data']['text']);
    }

    public function testSanitizingAllowedTags()
    {
        $data = '{"blocks":[{"type":"header","data":{"text":"<a>CodeX</a> <b>Editor</b> <a href=\"https://ifmo.su\">ifmo.su</a>","level":2}}]}';

        $editor = new CodexEditor($data, file_get_contents(TESTS_DIR . "/samples/test-config-allowed.json"));
        $result = $editor->sanitize();

        $this->assertEquals('<a>CodeX</a> <b>Editor</b> <a href="https://ifmo.su" target="_blank" rel="noreferrer noopener">ifmo.su</a>', $result[0]['data']['text']);
    }

    public function testCanBeOnly()
    {
        $callable = function () {
            new CodexEditor('{"blocks":[{"type":"header","data":{"text":"test","level":5}}]}', file_get_contents(TESTS_DIR . "/samples/test-config-allowed.json"));
        };

        $this->assertException($callable, CodexEditorException::class, null, 'Option \'level\' with value `5` has invalid value. Check canBeOnly param.');
    }
}
