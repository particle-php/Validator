<?php

use Particle\Validator\MessageStack;

class MessageChainTest extends PHPUnit_Framework_TestCase
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
        $ms->setMessages([
            'key' => [
                'reason' => 'This is my specific message'
            ]
        ]);

        $ms->append('key', 'reason', 'The invisible "default" message. {{key}}', ['key' => 'foo']);

        $this->assertEquals(
            [
                'key' => [
                    'reason' => 'This is my specific message',
                ]
            ],
            $ms->getMessages()
        );
    }
}
