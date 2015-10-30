<?php
namespace Particle\Validator\Tests;

use Particle\Validator\Failure;

class FailureTest extends \PHPUnit_Framework_TestCase
{
    public function testFailureCanReplacePlaceholders()
    {
        $failure = new Failure('key', 'reason', 'The value of "key" is "{{key}}"', ['key' => 'foo']);

        $this->assertEquals('The value of "key" is "foo"', $failure->format());
    }

    public function testIgnoresWhitespaceInPlaceholders()
    {
        $failure = new Failure('key', 'reason', 'The value of "key" is "{{   key    }}"', ['key' => 'foo']);

        $this->assertEquals('The value of "key" is "foo"', $failure->format());
    }

    public function testWillNotReplaceUnknownPlaceholders()
    {
        $failure = new Failure('key', 'reason', 'The value of "key" is "{{key}}"', []);

        $this->assertEquals('The value of "key" is "{{key}}"', $failure->format());
    }
}
