<?php
namespace Particle\Tests;

use Particle\Validator\MessageStack;
use Particle\Validator\ValidationResult;

class ValidationResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ValidationResult
     */
    protected $validationResult;

    /**
     * Set up a validation result with errors
     */
    public function setUp()
    {
        $messages = new MessageStack();
        $messages->append(
            'first_name',
            'tooShort',
            'Your username must be {{ min }} characters long',
            ['min' => 2]
        );
        $messages->append(
            'email',
            'invalid',
            'Your email must be a valid email address',
            []
        );

        $this->validationResult = new ValidationResult(false, $messages, $this->getTestValues());
    }

    private function getTestValues()
    {
        return [
            'first_name' => 'a',
            'last_name' => 'test',
            'email' => 'test',
        ];
    }

    public function testReturnsResultAndMessages()
    {
        $expectedMessages = [
            'first_name' => [
                'tooShort' => 'Your username must be 2 characters long',
            ],
            'email' => [
                'invalid' => 'Your email must be a valid email address',
            ],
        ];

        $this->assertFalse($this->validationResult->isValid());
        $this->assertTrue($this->validationResult->isNotValid());
        $this->assertEquals($expectedMessages, $this->validationResult->getMessages());
        $this->assertEquals($this->getTestValues(), $this->validationResult->getValues());
    }

    public function testOverwriteResultMessages()
    {
        $this->validationResult->overwriteMessages([
            'first_name' => [
                'tooShort' => 'Dewd, yo usa name should longa than {{ min }} bra!',
            ],
            'email' => [
                'invalid' => 'That ain\'t no email mate!'
            ]
        ]);

        $expectedMessages = [
            'first_name' => [
                'tooShort' => 'Dewd, yo usa name should longa than 2 bra!',
            ],
            'email' => [
                'invalid' => 'That ain\'t no email mate!'
            ]
        ];

        $this->assertEquals($expectedMessages, $this->validationResult->getMessages());
    }
}
