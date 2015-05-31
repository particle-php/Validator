<?php
namespace Particle\Tests;

use Particle\Validator\ValidationResult;
use Particle\Validator\Rule\Alpha;

class ValidationResultTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsResultAndMessages()
    {
        // lamest test ever, but then, the ValidationResult is only for convenience.
        $messages = [
            'first_name' => [
                Alpha::NOT_ALPHA => 'first name may only consist out of alphabetical characters'
            ]
        ];

        $result = new ValidationResult(false, $messages);
        $this->assertFalse($result->isValid());
        $this->assertEquals($messages, $result->getMessages());
    }
}
