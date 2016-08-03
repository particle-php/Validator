<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Integer;
use Particle\Validator\Validator;

class IntegerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    /**
     * @dataProvider getValidNonStrictIntegerValues
     * @param mixed $value
     */
    public function testReturnsTrueOnNonStrictValidInteger($value)
    {
        $this->validator->required('integer')->integer();
        $result = $this->validator->validate(['integer' => $value]);
        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getInvalidNonStrictIntegerValues
     * @param string $value
     */
    public function testReturnsFalseOnNonStrictInvalidIntegers($value)
    {
        $this->validator->required('integer')->integer();
        $result = $this->validator->validate(['integer' => $value]);
        $this->assertFalse($result->isValid());

        $expected = [
            'integer' => [
                Integer::NOT_AN_INTEGER => $this->getMessage(Integer::NOT_AN_INTEGER)
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * @dataProvider  getValidStrictIntegerValues
     *
     * @param mixed $value
     */
    public function testReturnsTrueOnStrictValidInteger($value)
    {
        $this->validator->required('integer')->integer(true);
        $result = $this->validator->validate(['integer' => 3]);
        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getInvalidStrictIntegerValues
     *
     * @param mixed $value
     */
    public function testReturnsFalseOnStrictInvalidIntegers($value)
    {
        $this->validator->required('integer')->integer(true);
        $result = $this->validator->validate(['integer' => $value]);
        $this->assertFalse($result->isValid());

        $expected = [
            'integer' => [
                Integer::NOT_AN_INTEGER => $this->getMessage(Integer::NOT_AN_INTEGER)
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    public function getValidNonStrictIntegerValues()
    {
        return [
            ['1337'],
            ['1211'],
            ['0'],
            [1231],
            [-12],
            ['-12'],
            [0xFF],
        ];
    }

    public function getValidStrictIntegerValues()
    {
        return [
            [3],
            [-10],
            [0b111],
            [0x111],
        ];
    }

    public function getInvalidNonStrictIntegerValues()
    {
        return [
            ['133.7'],
            ['a1211'],
        ];
    }

    public function getInvalidStrictIntegerValues()
    {
        return [
            ['123'],
            ['987.3'],
            [828.3],
            ['a11'],
        ];
    }

    public function getMessage($reason)
    {
        $messages = [
            Integer::NOT_AN_INTEGER => 'integer must be an integer'
        ];

        return $messages[$reason];
    }
}
