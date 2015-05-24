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

    public function testOverwritingKeyWillReuseExistingChainAndTheLatterRequirednessIsUsed()
    {
        $this->validator->required('foo');
        $this->validator->optional('foo');

        $result = $this->validator->validate([]);

        $this->assertTrue($result);
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

    public function testNoFalsePositivesForIssetOnFalse()
    {
        $this->validator->required('falsy_value');
        $result = $this->validator->validate([
            'falsy_value' => false,
        ]);

        $this->assertEquals([], $this->validator->getMessages());
        $this->assertTrue($result);
    }

    public function testReturnsEmptyArrayInsteadOfValidatedValues()
    {
        $this->validator->required('first_name')->lengthBetween(2, 20);
        $this->assertEquals([], $this->validator->getValues());
    }
}
