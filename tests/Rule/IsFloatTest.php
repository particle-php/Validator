<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\IsFloat;
use Particle\Validator\Validator;

class IsFloatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueOnValidFloat()
    {
        $value = 3.14;

        $this->validator->required('value')->float();

        $result = $this->validator->validate([
            'value' => $value,
        ]);

        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getInvalidFloatValues
     *
     * @param mixed $value
     */
    public function testReturnsFalseOnInvalidFloat($value)
    {
        $this->validator->required('value')->float();

        $result = $this->validator->validate([
            'value' => $value,
        ]);

        $this->assertFalse($result->isValid());

        $expected = [
            'value' => [
                IsFloat::NOT_A_FLOAT => $this->getMessage(IsFloat::NOT_A_FLOAT),
            ],
        ];

        $this->assertEquals($expected, $result->getMessages());
    }

    public function getInvalidFloatValues()
    {
        return [
            ['foo'],
            [9000],
            [true],
            [new \stdClass()],
        ];
    }

    private function getMessage($reason)
    {
        $messages = [
            IsFloat::NOT_A_FLOAT => 'value must be a float',
        ];

        return $messages[$reason];
    }
}
