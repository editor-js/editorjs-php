<?php

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function assertException(callable $callback, $expectedException = 'Exception', $expectedCode = null, $expectedMessage = null)
    {
        $expectedException = ltrim((string) $expectedException, '\\');

        if (!class_exists($expectedException) && !interface_exists($expectedException)) {
            $this->fail(sprintf('An exception of type "%s" does not exist.', $expectedException));
        }

        try {
            $callback();
        } catch (\Exception $e) {
            $class = get_class($e);
            $message = $e->getMessage();
            $code = $e->getCode();

            $errorMessage = 'Failed asserting the class of exception';

            if ($message && $code) {
                $errorMessage .= sprintf(' (message was %s, code was %d)', $message, $code);
            } elseif ($code) {
                $errorMessage .= sprintf(' (code was %d)', $code);
            }

            $errorMessage .= '.';
            $this->assertInstanceOf($expectedException, $e, $errorMessage);

            if ($expectedCode !== null) {
                $this->assertEquals($expectedCode, $code, sprintf('Failed asserting code of thrown %s.', $class));
            }

            if ($expectedMessage !== null) {
                $this->assertContains($expectedMessage, $message, sprintf('Failed asserting the message of thrown %s.', $class));
            }

            return;
        }

        $errorMessage = 'Failed asserting that exception';

        if (strtolower($expectedException) !== 'exception') {
            $errorMessage .= sprintf(' of type %s', $expectedException);
        }

        $errorMessage .= ' was thrown.';
        $this->fail($errorMessage);
    }
}
