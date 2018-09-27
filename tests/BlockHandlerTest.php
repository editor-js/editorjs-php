<?php

use CodexEditor\CodexEditor;

class BlockHandlerTest extends TestCase
{
    const SAMPLE_VALID_DATA = '{"time":1537455059200,"blocks":[{"type":"header","data":{"text":"CodeX Editor","level":2}},{"type":"paragraph","data":{"text":"Привет. Перед вами наш обновленный редактор. На этой странице вы можете проверить его в действии — <b>попробуйте</b> отредактировать или <i>дополнить</i> материал. Код страницы содержит пример подключения и простейшей настройки."}},{"type":"header","data":{"text":"234<b>0</b>","level":3}},{"type":"paragraph","data":{"text":"test&nbsp;"}},{"type":"quote","data":{"quote":"quoter","caption":"author","alignment":"left"}},{"type":"list","data":{"style":"ordered","items":["3e2","23e23e"]}},{"type":"paragraph","data":{"text":""}}],"version":"2.0.3"}';

    public function testLoad()
    {
        $editor = new CodexEditor(BlockHandlerTest::SAMPLE_VALID_DATA, TESTS_DIR . "/samples/test-config.json");
        $result = $editor->sanitize();
    }

    public function testSanitizing()
    {
        $data = '{"blocks":[{"type":"header","data":{"text":"CodeX <b>Editor</b>","level":2}}]}';

        $editor = new CodexEditor($data, TESTS_DIR . "/samples/test-config.json");
        $result = $editor->sanitize();

        $this->assertEquals($result[0]['data']['text'], 'CodeX Editor');
    }

    public function testSanitizingAllowedTags()
    {
        $data = '{"blocks":[{"type":"header","data":{"text":"<a>CodeX</a> <b>Editor</b>","level":2}}]}';

        $editor = new CodexEditor($data, TESTS_DIR . "/samples/test-config-allowed.json");
        $result = $editor->sanitize();

        $this->assertEquals($result[0]['data']['text'], 'CodeX <b>Editor</b>');
    }
}
