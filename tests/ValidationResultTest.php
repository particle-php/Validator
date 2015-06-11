<?php
namespace Particle\Tests;

use Particle\Validator\ValidationResult;
use Particle\Validator\Rule\Alpha;

class ValidationResultTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsResultAndMessages()
    {
        $values = [
            'first_name' => 'test',
        ];

        $messages = [
            'first_name' => [
                Alpha::NOT_ALPHA => 'first name may only consist out of alphabetical characters'
            ]
        ];

        $result = new ValidationResult(false, $messages, $values);
        $this->assertFalse($result->isValid());
        $this->assertTrue($result->isNotValid());
        $this->assertEquals($messages, $result->getMessages());
        $this->assertEquals($values, $result->getValues());
    }
}
