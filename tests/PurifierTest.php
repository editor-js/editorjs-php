<?php

use EditorJS\EditorJS;

/**
 * Class PurifierTest
 *
 * Check HTML Purification cases
 */
class PurifierTest extends TestCase
{
    const CONFIGURATION_FILE = TESTS_DIR . "/samples/purify-test-config.json";

    /**
     * Sample configuration
     */
    public $configuration;

    /**
     * Setup configuration
     */
    public function setUp()
    {
        $this->configuration = file_get_contents(PurifierTest::CONFIGURATION_FILE);
    }

    public function testHtmlPurifier()
    {
        $data = '{"time":1539180803359,"blocks":[{"type":"header","data":{"text":"<b>t</b><i>e</i><u>st</u>","level":2}}, {"type":"quote","data":{"text":"<b>t</b><i>e</i><u>st</u>","caption":"", "alignment":"left"}}]}';
        $editor = new EditorJS($data, $this->configuration);
        $result = $editor->getBlocks();

        $this->assertEquals(2, count($result));
        $this->assertEquals('test', $result[0]['data']['text']);
        $this->assertEquals('<b>t</b><i>e</i><u>st</u>', $result[1]['data']['text']);
    }

    public function testCustomTagPurifier()
    {
        $data = '{"time":1539180803359,"blocks":[{"type":"header","data":{"text":"<b>t</b><mark>e</mark><u>st</u>","level":2}}]}';
        $editor = new EditorJS($data, $this->configuration);
        $result = $editor->getBlocks();

        $this->assertEquals('t<mark>e</mark>st', $result[0]['data']['text']);
    }

    public function testAllTagsPurifier()
    {
        $data = '{"time":1539180803359,"blocks":[{"type":"raw","data":{"html": "<div style=\"background: #000; color: #fff; font-size: 30px; padding: 50px;\">Any HTML code</div>"}}]}';
        $editor = new EditorJS($data, $this->configuration);
        $result = $editor->getBlocks();

        $this->assertEquals('<div style="background: #000; color: #fff; font-size: 30px; padding: 50px;">Any HTML code</div>', $result[0]['data']['html']);
    }
}
