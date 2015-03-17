<?php

use Particle\Validator\MessageStack;
use Particle\Validator\Rule\Length;

class LengthTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Length
     */
    protected $rule;

    /**
     * @var MessageStack
     */
    protected $messageStack;

    public function setUp()
    {
        $this->messageStack = new MessageStack();

        $this->rule = new Length(5);
        $this->rule->setParameters('first_name', 'first name');
        $this->rule->setMessageStack($this->messageStack);
    }

    public function testTooShortWillLogTooShortError()
    {
        $this->assertFalse($this->rule->isValid('first_name', ['first_name' => 'Rick']));
        $expected = [
            'first_name' => [
                Length::TOO_SHORT => 'The value of "first name" is too short, should be 5 characters long'
            ]
        ];
        $this->assertEquals($expected, $this->messageStack->getMessages());
    }

    public function testTooLongWillLogTooLongError()
    {
        $this->assertFalse($this->rule->isValid('first_name', ['first_name' => 'Hendrick']));
        $expected = [
            'first_name' => [
                Length::TOO_LONG => 'The value of "first name" is too long, should be 5 characters long'
            ]
        ];
        $this->assertEquals($expected, $this->messageStack->getMessages());
    }

    public function testCorrectLengthWillLogNoErrors()
    {
        $this->assertTrue($this->rule->isValid('first_name', ['first_name' => 'Berry']));
        $this->assertEquals([], $this->messageStack->getMessages());
    }

    public function testShouldNotBreakChainOnFailure()
    {
        $this->assertFalse($this->rule->shouldBreakChain());
    }
}
