<?php
namespace Particle\Tests;

use Particle\Validator\MessageStack;
use Particle\Validator\Rule;
use Particle\Validator\Validator;
use Particle\Validator\Rule\Required;
use Particle\Validator\Value\Container;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsFalseOnNonExistentRequiredKey()
    {
        $this->validator->required('first_name');
        $result = $this->validator->validate([]);

        $this->assertFalse($result);

        $this->assertEquals(
            [
                'first_name' => [
                    Required::NON_EXISTENT_KEY => 'first_name must be provided, but does not exist',
                ]
            ],
            $this->validator->getMessages()
        );
    }

    public function testBreaksOnNonExistentRequiredKey()
    {
        $this->validator->required('first_name')->length(50);
        $result = $this->validator->validate([]);

        $this->assertFalse($result);

        $this->assertEquals(
            [
                'first_name' => [
                    Required::NON_EXISTENT_KEY => 'first_name must be provided, but does not exist',
                ]
            ],
            $this->validator->getMessages()
        );
    }

    public function testReturnsFalseOnExistingRequiredKeyDisallowingEmpty()
    {
        $this->validator->required('first_name', 'first name');
        $result = $this->validator->validate(['first_name' => '']);

        $this->assertFalse($result);

        $this->assertEquals(
            [
                'first_name' => [
                    Required::EMPTY_VALUE => 'first name must be provided and may not be empty'
                ]
            ],
            $this->validator->getMessages()
        );
    }

    public function testReturnsTrueOnExistingRequiredKeyAllowingEmpty()
    {
        $this->validator->required('first_name', 'first name', true);
        $result = $this->validator->validate(['first_name' => '']);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testReturnsTrueOnNonExistingOptionalKeyAllowingEmpty()
    {
        $this->validator->optional('first_name');
        $result = $this->validator->validate([]);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testReturnsTrueOnExistingKeyAllowingEmpty()
    {
        $this->validator->optional('first_name');
        $result = $this->validator->validate(['first_name' => 'Berry']);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testReturnsTrueOnNonExistingOptionalKeyDisallowingEmpty()
    {
        $this->validator->optional('first_name', 'first name', false)->length(5);
        $result = $this->validator->validate([]);

        $this->assertTrue($result);
    }

    public function testReturnFalseOnExistingOptionalKeyWithEmptyValueDisallowingEmpty()
    {
        $this->validator->optional('first_name', 'first name', false)->length(5);
        $result = $this->validator->validate(['first_name' => '']);

        $this->assertFalse($result);
        $this->assertEquals(
            [
                'first_name' => [
                    Required::EMPTY_VALUE => 'first name must be provided and may not be empty'
                ]
            ],
            $this->validator->getMessages()
        );
    }

    public function testRequiredCanBeConditional()
    {
        $this->validator->optional('first_name')->required(function (array $values) {
            return $values['foo'] === 'bar';
        });

        $result = $this->validator->validate(['foo' => 'bar']);

        $this->assertFalse($result);
        $this->assertEquals(
            [
                'first_name' => [
                    Required::NON_EXISTENT_KEY => 'first_name must be provided, but does not exist',
                ]
            ],
            $this->validator->getMessages()
        );

        $result = $this->validator->validate(['foo' => 'not bar!']);
        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testAllowEmptyCanBeConditional()
    {
        $this->validator->required('first_name', 'first name', true)->allowEmpty(function ($values) {
            return $values['foo'] !== 'bar';
        });

        $result = $this->validator->validate(['foo' => 'bar', 'first_name' => '']);

        $this->assertFalse($result);
        $this->assertEquals(
            [
                'first_name' => [
                    Required::EMPTY_VALUE => 'first name must be provided and may not be empty'
                ]
            ],
            $this->validator->getMessages()
        );

        $result = $this->validator->validate(['foo' => 'not bar!', 'first_name' => '']);
        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testCanOverwriteSpecificMessages()
    {
        $this->validator->required('foo');
        $this->validator->overwriteMessages([
            'foo' => [
                Required::NON_EXISTENT_KEY => 'This is my overwritten message. The key was "{{ key }}".'
            ]
        ]);

        $this->assertFalse($this->validator->validate([]));
        $this->assertEquals(
            [
                'foo' => [
                    Required::NON_EXISTENT_KEY => 'This is my overwritten message. The key was "foo".'
                ]
            ],
            $this->validator->getMessages()
        );
    }

    public function testOverwritingKeyWillReuseExistingChainButTheFirstRequirednessWillBeUsed()
    {
        $first = $this->validator->required('foo');
        $second = $this->validator->optional('foo');

        $stack = new MessageStack();
        $this->assertEquals($first, $second);
        $this->assertFalse($second->validate($stack, new Container([]), new Container([]))); // because it's required.
    }

    public function testDefaultMessageOverwrites()
    {
        $this->validator->overwriteDefaultMessages([
            Rule\Length::TOO_SHORT => 'this is my overwritten message. {{ length }} is the length.'
        ]);

        $this->validator->required('first_name', 'Voornaam')->length(5);
        $this->assertFalse($this->validator->validate(['first_name' => 'Rick']));

        $expected = [
            'first_name' => [
                Rule\Length::TOO_SHORT => 'this is my overwritten message. 5 is the length.'
            ]
        ];

        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testSpecificMessageWillHavePrecedenceOverDefaultMessage()
    {
        $this->validator->overwriteDefaultMessages([
            Rule\Length::TOO_SHORT => 'This is overwritten globally.'
        ]);

        $this->validator->overwriteMessages([
            'first_name' => [
                Rule\Length::TOO_SHORT => 'This is overwritten for first_name only.'
            ]
        ]);

        $this->validator->required('first_name')->length(5);
        $this->assertFalse($this->validator->validate(['first_name' => 'Rick']));

        $expected = [
            'first_name' => [
                Rule\Length::TOO_SHORT => 'This is overwritten for first_name only.'
            ]
        ];

        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testReturnsValidatedValues()
    {
        $this->validator->required('first_name')->lengthBetween(2, 20);
        $this->validator->required('last_name')->lengthBetween(2, 60);

        $this->validator->validate([
            'first_name' => 'Berry',
            'last_name' => 'Langerak',
            'is_admin' => true
        ]);

        $expected = [
            'first_name' => 'Berry',
            'last_name' => 'Langerak',
        ];

        $this->assertEquals($expected, $this->validator->getValues());
    }

    public function testReturnsEmptyArrayInsteadOfValidatedValues()
    {
        $this->validator->required('first_name')->lengthBetween(2, 20);
        $this->assertEquals([], $this->validator->getValues());
    }
}
