<?php
namespace Particle\Validator\Tests;

use Particle\Validator\Failure;
use Particle\Validator\MessageStack;
use Particle\Validator\Rule\NotEmpty;
use Particle\Validator\Rule\Required;

class MessageChainTest extends \PHPUnit_Framework_TestCase
{
    public function testMergeWillMergeMessagesOfOtherMessageStacks()
    {
        $stack = new MessageStack();
        $stackTwo = new MessageStack();

        $stack->overwriteMessages([
            'foo' => [
                Required::NON_EXISTENT_KEY => 'Non existent key',
            ]
        ]);

        $stack->overwriteDefaultMessages([
            NotEmpty::EMPTY_VALUE => 'Empty value',
        ]);

        $stackTwo->merge($stack);

        $messages = [
            $stackTwo->getOverwrite(Required::NON_EXISTENT_KEY, 'foo'),
            $stackTwo->getOverwrite(NotEmpty::EMPTY_VALUE, 'bar')
        ];

        $expected = [
            'Non existent key',
            'Empty value'
        ];

        $this->assertEquals($expected, $messages);
    }

    public function testOverwritesDefaultMessage()
    {
        $stack = new MessageStack();

        $stack->overwriteDefaultMessages([
            NotEmpty::EMPTY_VALUE => 'Empty value',
        ]);

        $stack->append(new Failure('foo', NotEmpty::EMPTY_VALUE, 'Not important', []));

        $expected = [
            new Failure('foo', NotEmpty::EMPTY_VALUE, 'Empty value', [])
        ];

        $this->assertEquals($expected, $stack->getFailures());
    }

    public function testOverwritesSpecificMessage()
    {
        $stack = new MessageStack();

        $stack->overwriteMessages([
            'foo' => [
                NotEmpty::EMPTY_VALUE => 'Empty value',
            ]
        ]);

        $stack->append(new Failure('foo', NotEmpty::EMPTY_VALUE, 'Not important', []));

        $expected = [
            new Failure('foo', NotEmpty::EMPTY_VALUE, 'Empty value', [])
        ];

        $this->assertEquals($expected, $stack->getFailures());
    }
}
