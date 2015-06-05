<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\Integer;
use Particle\Validator\Validator;

class IntegerTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getValidIntegerValues
     * @param mixed $value
     */
    public function testReturnsTrueOnValidInteger($value)
    {
        $this->validator->required('integer')->integer();
        $this->assertTrue($this->validator->isValid(['integer' => $value]));
    }

    /**
     * @dataProvider getInvalidIntegerValues
     * @param string $value
     */
    public function testReturnsFalseOnInvalidIntegers($value)
    {
        $this->validator->required('integer')->integer();
        $this->assertFalse($this->validator->isValid(['integer' => $value]));

        $expected = [
            'integer' => [
                Integer::NOT_AN_INTEGER => $this->getMessage(Integer::NOT_AN_INTEGER)
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function getValidIntegerValues()
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

    public function getInvalidIntegerValues()
    {
        return [
            ['133.7'],
            ['a1211'],
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
