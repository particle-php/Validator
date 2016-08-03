<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Numeric;
use Particle\Validator\Validator;

class NumericTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getValidNumericValues
     * @param mixed $value
     */
    public function testReturnsTrueOnValidNumeric($value)
    {
        $this->validator->required('number')->numeric();
        $result = $this->validator->validate(['number' => $value]);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider getInvalidNumericValues
     * @param string $value
     */
    public function testReturnsFalseOnInvalidNumeric($value)
    {
        $this->validator->required('number')->numeric();
        $result = $this->validator->validate(['number' => $value]);

        $this->assertFalse($result->isValid());

        $expected = [
            'number' => [
                Numeric::NOT_NUMERIC => $this->getMessage(Numeric::NOT_NUMERIC)
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    public function getValidNumericValues()
    {
        return [
            ['133.7'],
            [133.7],
            ['1337'],
            ['1211'],
            ['0'],
            [1231],
            [-12],
            ['-12'],
            [0xFF],
        ];
    }

    public function getInvalidNumericValues()
    {
        return [
            ['a1211'],
            ['not even a number in sight!']
        ];
    }

    private function getMessage($reason)
    {
        $messages = [
            Numeric::NOT_NUMERIC => 'number must be numeric'
        ];

        return $messages[$reason];
    }
}
