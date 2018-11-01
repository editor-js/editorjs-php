<?php

use EditorJS\EditorJS;
use EditorJS\EditorJSException;

/**
 * Class SyntaxSugarTest
 *
 * Check simplified configuration structure
 */
class SyntaxSugarTest extends TestCase
{
    const CONFIGURATION_FILE = TESTS_DIR . "/samples/syntax-sugar.json";

    /**
     * Sample configuration
     */
    public $configuration;

    /**
     * Setup configuration
     */
    public function setUp()
    {
        $this->configuration = file_get_contents(SyntaxSugarTest::CONFIGURATION_FILE);
    }

    public function testShortTypeField()
    {
        $data = '{"blocks":[{"type":"header","data":{"text":"CodeX <b>Editor</b>", "level": 2}}]}';

        $editor = new EditorJS($data, $this->configuration);
        $result = $editor->getBlocks();

        $this->assertEquals('CodeX Editor', $result[0]['data']['text']);
        $this->assertEquals(2, $result[0]['data']['level']);
    }

    public function testShortTypeFieldCanBeOnly()
    {
        $callable = function () {
            new EditorJS('{"blocks":[{"type":"header","data":{"text":"CodeX <b>Editor</b>", "level": 5}}]}',
                $this->configuration);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Option \'level\' with value `5` has invalid value. Check canBeOnly param.');
    }

    public function testShortIntValid()
    {
        new EditorJS('{"blocks":[{"type":"subtitle","data":{"text": "string", "level": 1337}}]}', $this->configuration);
    }

    public function testShortIntNotValid()
    {
        $callable = function () {
            new EditorJS('{"blocks":[{"type":"subtitle","data":{"text": "test", "level": "string"}}]}', $this->configuration);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Option \'level\' with value `string` must be integer');
    }

    public function testInvalidType()
    {
        $callable = function () {
            $invalid_configuration = '{"tools": {"header": {"title": "invalid_type"}}}';
            new EditorJS('{"blocks":[{"type":"header","data":{"title": "test"}}]}', $invalid_configuration);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Unhandled type `invalid_type`');
    }

    public function testMixedStructure()
    {
        $data = '{"time":1539180803359,"blocks":[{"type":"header","data":{"text":"<b>t</b><i>e</i><u>st</u>","level":2}}, {"type":"quote","data":{"text":"<b>t</b><i>e</i><u>st</u>","caption":"", "alignment":"left"}}]}';
        $editor = new EditorJS($data, $this->configuration);
        $result = $editor->getBlocks();

        $this->assertEquals(2, count($result));
        $this->assertEquals('test', $result[0]['data']['text']);
        $this->assertEquals('<b>t</b><i>e</i>st', $result[1]['data']['text']);
    }
}
