<?php
namespace Particle\Validator\Tests;

use Particle\Validator\MessageStack;
use Particle\Validator\Rule\NotEmpty;
use Particle\Validator\Rule\Required;

class MessageChainTest extends \PHPUnit_Framework_TestCase
{
    public function testCanFormatMessagesByReplacingPlaceholders()
    {
        $ms = new MessageStack();
        $ms->append('key', 'reason', 'The value of "key" is "{{key}}"', ['key' => 'foo']);
        $result = $ms->getMessages()['key']['reason'];

        $this->assertEquals('The value of "key" is "foo"', $result);
    }

    public function testWillIgnoreWhitespaceInPlaceholders()
    {
        $ms = new MessageStack();
        $ms->append('key', 'reason', 'The value of "key" is "{{    key  }}"', ['key' => 'foo']);
        $result = $ms->getMessages()['key']['reason'];

        $this->assertEquals('The value of "key" is "foo"', $result);
    }

    public function testWillNotReplaceUnknownPlaceholder()
    {
        $ms = new MessageStack();
        $ms->append('key', 'reason', 'The value of "key" is "{{ key }}"', []);
        $result = $ms->getMessages()['key']['reason'];

        $this->assertEquals('The value of "key" is "{{ key }}"', $result);
    }

    public function testWillAppendMessagesToTheKeyAndReason()
    {
        $ms = new MessageStack();
        $ms->append('key', 'reason', 'This is the message', []);
        $ms->append('key', 'reason2', 'This is another message', []);

        $this->assertEquals(
            [
                'key' => [
                    'reason' => 'This is the message',
                    'reason2' => 'This is another message'
                ]
            ],
            $ms->getMessages()
        );
    }

    public function testCanOverwriteSpecificMessagesWithParameters()
    {
        $ms = new MessageStack();
        $ms->overwriteMessages([
            'key' => [
                'reason' => 'This is my specific message. The key was "{{key}}"'
            ]
        ]);

        $ms->append('key', 'reason', 'The invisible "default" message. {{key}}', ['key' => 'foo']);

        $expected = [
            'key' => [
                'reason' => 'This is my specific message. The key was "foo"',
            ]
        ];

        $this->assertEquals($expected, $ms->getMessages());
    }

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
}
