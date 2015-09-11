<?php
namespace Particle\Validator\Tests;

use Particle\Validator\Output\Structure;
use Particle\Validator\Rule;
use Particle\Validator\Tests\Support\Statement;
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
    public function testUnconfiguredValidatorWillNotShowNotice()
    {
        $this->assertTrue($this->validator->validate(['value' => 'yes'])->isValid());
    }

    public function testOutputWillGiveRepresentationOfInternalStructure()
    {
        $callable = function (Structure $structure) {
            $output = [];
            $subjects = $structure->getSubjects();
            foreach ($subjects as $subject) {
                foreach ($subject->getRules() as $rule) {
                    $output[$subject->getKey()][] = [
                        'rule' => $rule->getName(),
                        'messages' => $rule->getMessages(),
                        'parameters' => $rule->getParameters(),
                    ];
                }
            }

            return $output;
        };

        $this->validator->required('email')->email();
        $this->validator->optional('firstname')->allowEmpty(true)->lengthBetween(0, 20);

        $definition = $this->validator->output($callable);

        $expected = [
            'email' => [
                [
                    'rule' => 'Required',
                    'messages' => [
                        Required::NON_EXISTENT_KEY => '{{ key }} must be provided, but does not exist',
                    ],
                    'parameters' => [
                        'key' => 'email',
                        'name' => 'email',
                        'required' => true,
                        'callback' => null,
                    ]
                ],
                [
                    'rule' => 'NotEmpty',
                    'messages' => [
                        Rule\NotEmpty::EMPTY_VALUE => '{{ name }} must not be empty',
                    ],
                    'parameters' => [
                        'key' => 'email',
                        'name' => 'email',
                        'allowEmpty' => false,
                        'callback' => null,
                    ]
                ],
                [
                    'rule' => 'Email',
                    'messages' => [
                        Rule\Email::INVALID_FORMAT => '{{ name }} must be a valid email address',
                    ],
                    'parameters' => [
                        'key' => 'email',
                        'name' => 'email',
                    ]
                 ]
            ],
            'firstname' => [
                [
                    'rule' => 'Required',
                    'messages' => [
                        Required::NON_EXISTENT_KEY => '{{ key }} must be provided, but does not exist',
                    ],
                    'parameters' => [
                        'key' => 'firstname',
                        'name' => 'firstname',
                        'required' => false,
                        'callback' => null,
                    ],
                ],
                [
                    'rule' => 'NotEmpty',
                    'messages' => [
                        Rule\NotEmpty::EMPTY_VALUE => '{{ name }} must not be empty',
                    ],
                    'parameters' => [
                        'key' => 'firstname',
                        'name' => 'firstname',
                        'allowEmpty' => true,
                        'callback' => null,
                    ],
                ],
                [
                    'rule' => 'LengthBetween',
                    'messages' => [
                        'LengthBetween::TOO_LONG' => '{{ name }} must be shorter than {{ max }} characters',
                        'LengthBetween::TOO_SHORT' => '{{ name }} must be longer than {{ min }} characters',
                    ],
                    'parameters' => [
                        'key' => 'firstname',
                        'name' => 'firstname',
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $definition);
    }

    public function testOutputWillGiveStatementIfItImplementsToString()
    {
        $this->validator->required('foo')
            ->required(new Statement('is required', false))
            ->allowEmpty(new Statement('is empty allowed', false))
            ->callback(new Statement('callback content', false));

        $callback = function (Structure $structure) {
            $rules = $structure->getSubjects()[0]->getRules();

            $this->assertEquals('is required', $rules[0]->getParameters()['callback']);
            $this->assertEquals('is empty allowed', $rules[1]->getParameters()['callback']);
            $this->assertEquals('callback content', $rules[2]->getParameters()['callback']);
        };

        $this->validator->output($callback);
    }
}
