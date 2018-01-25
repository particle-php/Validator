<?php

namespace Particle\Validator\Tests;

use Particle\Validator\Rule;
use Particle\Validator\Rule\Boolean;
use Particle\Validator\Rule\Integer;
use Particle\Validator\Rule\IsArray;
use Particle\Validator\Rule\IsFloat;
use Particle\Validator\Rule\IsString;
use Particle\Validator\Rule\LengthBetween;
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
                CustomRule::NOT_BAR => 'foo must be equal to "bar"',
            ],
        ];

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * @dataProvider providePrimitiveRulesData
     *
     * @param Rule $rule
     * @param array $data
     * @param array $expected
     */
    public function testBreakChain($rule, $data, $expected)
    {
        $this
            ->validator
            ->required('foo')
            ->mount($rule)
            ->lengthBetween(1, 50);

        $result = $this->validator->validate($data);

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    public function testBreakChainOnFailure()
    {
        $this
            ->validator
            ->required('foo')
            ->string()
            ->lengthBetween(2, 5);

        $result = $this->validator->validate(['foo' => 'abcdefg']);

        $this->assertFalse($result->isValid());
        $this->assertEquals(
            [
                'foo' => [
                    LengthBetween::TOO_LONG => 'foo must be 5 characters or shorter',
                ],
            ],
            $result->getMessages()
        );
    }

    public function testBreakChainOnSuccess()
    {
        $this
            ->validator
            ->required('foo')
            ->string()
            ->lengthBetween(2, 5);

        $result = $this->validator->validate(['foo' => 'abc']);

        $this->assertTrue($result->isValid());
    }

    /**
     * @return array
     */
    public function providePrimitiveRulesData()
    {
        return [
            [
                new Boolean(),
                [
                    'foo' => 'string',
                ],
                [
                    'foo' => [
                        Boolean::NOT_BOOL => 'foo must be either true or false',
                    ],
                ],
            ],
            [
                new Integer(),
                [
                    'foo' => 'string',
                ],
                [
                    'foo' => [
                        Integer::NOT_AN_INTEGER => 'foo must be an integer',
                    ],
                ],
            ],
            [
                new IsArray(),
                [
                    'foo' => 'string',
                ],
                [
                    'foo' => [
                        IsArray::NOT_AN_ARRAY => 'foo must be an array',
                    ],
                ],
            ],
            [
                new IsFloat(),
                [
                    'foo' => 'string',
                ],
                [
                    'foo' => [
                        IsFloat::NOT_A_FLOAT => 'foo must be a float',
                    ],
                ],
            ],
            [
                new IsString(),
                [
                    'foo' => ['array-value'],
                ],
                [
                    'foo' => [
                        IsString::NOT_A_STRING => 'foo must be a string',
                    ],
                ],
            ],
        ];
    }
}
