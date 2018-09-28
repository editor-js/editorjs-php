<?php

use CodexEditor\CodexEditor;
use CodexEditor\CodexEditorException;

/**
 * Class TypeTest
 *
 * Check basic types: integer, boolean
 */
class TypeTest extends TestCase
{
    const CONFIGURATION_FILE = TESTS_DIR . "/samples/type-test-config.json";

    /**
     * Sample configuration
     */
    public $configuration;

    /**
     * Setup configuration
     */
    public function setUp()
    {
        $this->configuration = file_get_contents(TypeTest::CONFIGURATION_FILE);
    }

    public function testBooleanFailed()
    {
        $callable_not_bool = function () {
            new CodexEditor('{"blocks":[{"type":"test","data":{"bool_test":"not boolean"}}]}', $this->configuration);
        };

        $this->assertException($callable_not_bool, CodexEditorException::class, null, 'Option \'bool_test\' with value `not boolean` must be boolean');
    }

    public function testBooleanValid()
    {
        new CodexEditor('{"blocks":[{"type":"test","data":{"bool_test":true}}]}', $this->configuration);
    }

    public function testIntegerValid()
    {
        new CodexEditor('{"blocks":[{"type":"test","data":{"int_test": 5}}]}', $this->configuration);
    }

    public function testIntegerFailed()
    {
        $callable = function () {
            new CodexEditor('{"blocks":[{"type":"test","data":{"int_test": "not integer"}}]}', $this->configuration);
        };

        $this->assertException($callable, CodexEditorException::class, null, 'Option \'int_test\' with value `not integer` must be integer');
    }

    public function testStringValid()
    {
        new CodexEditor('{"blocks":[{"type":"test","data":{"string_test": "string"}}]}', $this->configuration);
    }

    public function testStringFailed()
    {
        $callable = function () {
            new CodexEditor('{"blocks":[{"type":"test","data":{"string_test": 17}}]}', $this->configuration);
        };

        $this->assertException($callable, CodexEditorException::class, null, 'Option \'string_test\' with value `17` must be string');
    }
}
