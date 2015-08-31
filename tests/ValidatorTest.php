<?php
namespace Particle\Tests;

use Particle\Validator\Rule;
use Particle\Validator\Validator;
use Particle\Validator\Rule\Required;

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
        $result = $this->validator->validate([]);

        $this->assertFalse($result->isValid());
        $this->assertEquals(
            [
                'foo' => [
                    Required::NON_EXISTENT_KEY => 'This is my overwritten message. The key was "foo".'
                ]
            ],
            $result->getMessages()
        );
    }

    public function testOverwritingKeyWillReuseExistingChainAndTheLatterRequirednessIsUsed()
    {
        $this->validator->required('foo');
        $this->validator->optional('foo');

        $result = $this->validator->validate([]);

        $this->assertTrue($result->isValid());
    }

    public function testDefaultMessageOverwrites()
    {
        $this->validator->overwriteDefaultMessages([
            Rule\Length::TOO_SHORT => 'this is my overwritten message. {{ length }} is the length.'
        ]);
        $this->validator->required('first_name', 'Voornaam')->length(5);
        $result = $this->validator->validate(['first_name' => 'Rick']);

        $expected = [
            'first_name' => [
                Rule\Length::TOO_SHORT => 'this is my overwritten message. 5 is the length.'
            ]
        ];

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
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

        $result = $this->validator->validate(['first_name' => 'Rick']);
        $this->assertFalse($result->isValid());

        $expected = [
            'first_name' => [
                Rule\Length::TOO_SHORT => 'This is overwritten for first_name only.'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    public function testReturnsValidatedValues()
    {
        $this->validator->required('first_name')->lengthBetween(2, 20);
        $this->validator->required('last_name')->lengthBetween(2, 60);

        $result = $this->validator->validate([
            'first_name' => 'Berry',
            'last_name' => 'Langerak',
            'is_admin' => true
        ]);

        $expected = [
            'first_name' => 'Berry',
            'last_name' => 'Langerak',
        ];

        $this->assertEquals($expected, $result->getValues());
    }

    public function testNoFalsePositivesForIssetOnFalse()
    {
        $this->validator->required('falsy_value');
        $result = $this->validator->validate([
            'falsy_value' => false,
        ]);

        $this->assertEquals([], $result->getMessages());
        $this->assertTrue($result->isValid());
    }

    public function testCanUseDotNotationToValidateInArrays()
    {
        $this->validator->required('user.contact.email')->email();

        $result = $this->validator->validate([
            'user' => [
                'contact' => [
                    'email' => 'example@particle-php.com'
                ]
            ]
        ]);

        $this->assertTrue($result->isValid());
    }

    public function testDotNotationIsAddedToMessagesVerbatim()
    {
        $this->validator->required('user.email');
        $result = $this->validator->validate([]);

        $expected = [
            'user.email' => [
                Required::NON_EXISTENT_KEY => 'user.email must be provided, but does not exist'
            ]
        ];

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    public function testDotNotationIsAlsoUsedForOutputValueContainer()
    {
        $input = [
            'user' => [
                'email' => 'example@particle-php.com'
            ]
        ];
        $this->validator->required('user.email');
        $result = $this->validator->validate($input);
        $this->assertEquals($input, $result->getValues());
    }

    public function testDotNotationWillReturnTrueForNullRequiredValue()
    {
        $this->validator->required('user.email', 'user email address', true);

        $result = $this->validator->validate([
            'user' => [
                'email' => null,
            ]
        ]);

        $this->assertTrue($result->isValid());
    }

    /**
     * Bug fix test: Check if no notice is shown when no validation rules are configured.
     */
    public function testNotConfiguredValidatorWillNotShowNotice()
    {
        $this->assertTrue($this->validator->validate(['value' => 'yes'])->isValid());
    }
}
