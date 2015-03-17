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
        $this->rule->isValid('first_name', ['first_name' => 'Rick']);

    }
}
