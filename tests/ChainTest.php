<?php
namespace Particle\Validator\Tests;

use Particle\Validator\Rule;
use Particle\Validator\Rule\Boolean;
use Particle\Validator\Rule\Each;
use Particle\Validator\Rule\Email;
use Particle\Validator\Rule\GreaterThan;
use Particle\Validator\Rule\InArray;
use Particle\Validator\Rule\Integer;
use Particle\Validator\Rule\IsArray;
use Particle\Validator\Rule\IsFloat;
use Particle\Validator\Rule\IsString;
use Particle\Validator\Rule\LengthBetween;
use Particle\Validator\Rule\Required;
use Particle\Validator\Tests\Support\CustomRule;
use Particle\Validator\Validator;

class ChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testCanMountRulesOnChain()
    {
        $rule = new CustomRule();

        $this->validator->required('foo')->mount($rule);

        $result = $this->validator->validate(['foo' => 'not bar']);

        $expected = [
            'foo' => [
                CustomRule::NOT_BAR => 'foo must be equal to "bar"'
            ]
        ];

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * @dataProvider provideBreakChainData
     *
     * @param Rule $firstRule
     * @param Rule $secondRule
     * @param array $data
     * @param array $expected
     */
    public function testBreakChain($firstRule, $secondRule, $data, $expected)
    {
        $this
            ->validator
            ->required('foo')
            ->mount($firstRule)
            ->mount($secondRule);

        $result = $this->validator->validate($data);

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * @return array
     */
    public function provideBreakChainData()
    {
        return [
            'break boolean rule on error' => [
                new Boolean(),
                new InArray([true, false]),
                [
                    'foo' => 'string',
                ],
                [
                    'foo' => [
                        Boolean::NOT_BOOL => 'foo must be either true or false',
                    ],
                ],
            ],
            'break integer rule on error' => [
                new Integer(),
                new GreaterThan(10),
                [
                    'foo' => 'string',
                ],
                [
                    'foo' => [
                        Integer::NOT_AN_INTEGER => 'foo must be an integer',
                    ],
                ],
            ],
            'break isArray rule on error' => [
                new IsArray(),
                new Each(function ($v) {
                    /** @var Validator $v */
                    $v->required('bar')->email();
                }),
                [
                    'foo' => 'string',
                ],
                [
                    'foo' => [
                        IsArray::NOT_AN_ARRAY => 'foo must be an array',
                    ],
                ],
            ],
            'break isFloat rule on error' => [
                new IsFloat(),
                new GreaterThan(20),
                [
                    'foo' => 'string',
                ],
                [
                    'foo' => [
                        IsFloat::NOT_A_FLOAT => 'foo must be a float',
                    ],
                ],
            ],
            'break isString rule on error' => [
                new IsString(),
                new LengthBetween(1, 3),
                [
                    'foo' => ['array-value'],
                ],
                [
                    'foo' => [
                        IsString::NOT_A_STRING => 'foo must be a string',
                    ],
                ],
            ],
            'break required rule' => [
                new Boolean(),
                new InArray([true, false]),
                [],
                [
                    'foo' => [
                        Required::NON_EXISTENT_KEY => 'foo must be provided, but does not exist',
                    ],
                ],
            ],
            'do not break boolean rule' => [
                new Boolean(),
                new InArray([false]),
                [
                    'foo' => true,
                ],
                [
                    'foo' => [
                        InArray::NOT_IN_ARRAY => 'foo must be in the defined set of values',
                    ],
                ],
            ],
            'do not break integer rule' => [
                new Integer(),
                new GreaterThan(10),
                [
                    'foo' => 5,
                ],
                [
                    'foo' => [
                        GreaterThan::NOT_GREATER_THAN => 'foo must be greater than 10',
                    ],
                ],
            ],
            'do not break isArray rule' => [
                new IsArray(),
                new Each(function (Validator $v) {
                    $v->required('bar')->email();
                }),
                [
                    'foo' => [
                        ['bar' => 'invalid@email'],
                    ],
                ],
                [
                    'foo.0.bar' => [
                        Email::INVALID_FORMAT => 'bar must be a valid email address',
                    ],
                ],
            ],
            'do not break isFloat rule' => [
                new IsFloat(),
                new GreaterThan(20),
                [
                    'foo' => 5.00,
                ],
                [
                    'foo' => [
                        GreaterThan::NOT_GREATER_THAN => 'foo must be greater than 20',
                    ],
                ],
            ],
            'do not break isString rule' => [
                new IsString(),
                new LengthBetween(1, 3),
                [
                    'foo' => 'abcdefg',
                ],
                [
                    'foo' => [
                        LengthBetween::TOO_LONG => 'foo must be 3 characters or shorter',
                    ],
                ],
            ],
        ];
    }
}
