<?php
use CodexEditor\CodexEditor;

class GeneralTest extends TestCase
{
    const SAMPLE_VALID_DATA = '{"time":1537444483710,"blocks":[{"type":"header","data":{"text":"CodeX Editor","level":2}},{"type":"paragraph","data":{"text":"Привет. Перед вами наш обновленный редактор. На этой странице вы можете проверить его в действии — попробуйте отредактировать или дополнить материал. Код страницы содержит пример подключения и простейшей настройки."}}],"version":"2.0.3"}';
    const EMPTY_DATA = '';

    public function testValidData()
    {
        new CodexEditor( GeneralTest::SAMPLE_VALID_DATA );
    }

    public function testNullInput()
    {
        $callable = function() {
            new CodexEditor('');
        };
        $this->assertException($callable, Exception::class, null, 'Wrong JSON format: Syntax error');
    }

    public function testEmptyArray()
    {
        $callable = function() {
            new CodexEditor('{}');
        };
        $this->assertException($callable, Exception::class, null, 'Input array is empty');
    }

    public function testWrongJson()
    {
        $callable = function() {
            new CodexEditor('{[{');
        };
        $this->assertException($callable, Exception::class, null, 'Wrong JSON format: Syntax error');
    }

    public function testItemsMissed()
    {
        $callable = function() {
            new CodexEditor('{"s":""}');
        };
        $this->assertException($callable, Exception::class, null, 'Items missed');
    }

    public function testUnicode()
    {
        $callable = function() {
            new CodexEditor('{"s":"😀"}');
        };
        $this->assertException($callable, Exception::class, null, 'Items missed');
    }

    public function testEmptyBlocks()
    {
        $callable = function() {
            new CodexEditor('{"blocks":[]}');
        };
        $this->assertException($callable, Exception::class, null, 'Input blocks are empty');
    }

    public function testInvalidBlock()
    {
        $callable = function() {
            new CodexEditor('{"blocks":""}');
        };
        $this->assertException($callable, Exception::class, null, 'Blocks is not an array');
    }

    public function testBlocksContent()
    {
        $callable = function() {
            new CodexEditor('{"blocks":["",""]}');
        };
        $this->assertException($callable, Exception::class, null, 'Block must be an Array');
    }

}