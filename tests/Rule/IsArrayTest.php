<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\IsArray;
use Particle\Validator\Rule\NotEmpty;
use Particle\Validator\Validator;

class IsArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    /**
     * @dataProvider getValidArrayValues
     * @param mixed $value
     */
    public function testReturnsTrueOnValidArray($value)
    {
        $this->validator->required('array')->isArray();
        $result = $this->validator->validate(['array' => $value]);

        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getInvalidArrayValues
     * @param mixed $value
     */
    public function testReturnsFalseOnInvalidArrays($value)
    {
        $this->validator->required('array')->isArray();
        $result = $this->validator->validate(['array' => $value]);
        $this->assertFalse($result->isValid());

        $expected = [
            'array' => [
                IsArray::NOT_AN_ARRAY => $this->getMessage(IsArray::NOT_AN_ARRAY)
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }


    public function testReturnsFalseOnEmptyArray()
    {
        $value = [];
        $this->validator->required('array')->isArray();
        $result = $this->validator->validate(['array' => $value]);
        $this->assertFalse($result->isValid());

        $expected = [
            'array' => [
                NotEmpty::EMPTY_VALUE => 'array must not be empty',
            ]
        ];

        $this->assertEquals($expected, $result->getMessages());
    }

    public function getValidArrayValues()
    {
        return [
            [[1, 2]],
            [['a' => 1, 'b' => 2]],
        ];
    }

    public function getInvalidArrayValues()
    {
        return [
            ['abc'],
            [123],
            [123.45],
            [new \stdClass()],
        ];
    }

    public function getMessage($reason)
    {
        $messages = [
            IsArray::NOT_AN_ARRAY => 'array must be an array'
        ];

        return $messages[$reason];
    }
}
