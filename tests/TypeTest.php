<?php

use EditorJS\EditorJS;
use EditorJS\EditorJSException;

/**
 * Class TypeTest
 *
 * Check basic types: integer, boolean
 */
class TypeTest extends TestCase
{
    const CONFIGURATION_FILE = TESTS_DIR . "/samples/type-test-config.json";
    const CONFIGURATION_FILE_REQUIRED = TESTS_DIR . "/samples/type-test-config-required.json";

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
            new EditorJS('{"blocks":[{"type":"test","data":{"bool_test":"not boolean"}}]}', $this->configuration);
        };

        $this->assertException($callable_not_bool, EditorJSException::class, null, 'Option \'bool_test\' with value `not boolean` must be boolean');
    }

    public function testBooleanValid()
    {
        new EditorJS('{"blocks":[{"type":"test","data":{"bool_test":true}}]}', $this->configuration);
    }

    public function testIntegerValid()
    {
        new EditorJS('{"blocks":[{"type":"test","data":{"int_test": 5}}]}', $this->configuration);
    }

    public function testIntegerFailed()
    {
        $callable = function () {
            new EditorJS('{"blocks":[{"type":"test","data":{"int_test": "not integer"}}]}', $this->configuration);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Option \'int_test\' with value `not integer` must be integer');
    }

    public function testStringValid()
    {
        new EditorJS('{"blocks":[{"type":"test","data":{"string_test": "string"}}]}', $this->configuration);
    }

    public function testStringFailed()
    {
        $callable = function () {
            new EditorJS('{"blocks":[{"type":"test","data":{"string_test": 17}}]}', $this->configuration);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Option \'string_test\' with value `17` must be string');
    }

    public function testAllowedNullNotRequired()
    {
        new EditorJS('{"blocks":[{"type":"test","data":{"int_test": null}}]}', $this->configuration);
    }

    public function testDisallowedNullNotRequired()
    {
        $callable = function () {
            new EditorJS('{"blocks":[{"type":"test","data":{"string_test": null}}]}', $this->configuration);
        };

        $this->assertException($callable, EditorJSException::class, null, 'string_test\' with value `` must be string');
    }

    public function testNullRequired()
    {
        new EditorJS('{"blocks":[{"type":"test","data":{"string_test": "qwe"}}]}', file_get_contents(TypeTest::CONFIGURATION_FILE_REQUIRED));

        $callable = function () {
            new EditorJS('{"blocks":[{"type":"test","data":{"string_test": null}}]}', file_get_contents(TypeTest::CONFIGURATION_FILE_REQUIRED));
        };
        $this->assertException($callable, EditorJSException::class, null, 'Not found required param `string_test`');
    }
}
