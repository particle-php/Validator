<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\IsString;
use Particle\Validator\Validator;

class IsStringTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueOnValidString()
    {
        $value = 'foo';

        $this->validator->required('value')->string();

        $result = $this->validator->validate([
            'value' => $value,
        ]);

        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getInvalidStringValues
     *
     * @param mixed $value
     */
    public function testReturnsFalseOnInvalidString($value)
    {
        $this->validator->required('value')->string();

        $result = $this->validator->validate([
            'value' => $value,
        ]);

        $this->assertFalse($result->isValid());

        $expected = [
            'value' => [
                IsString::NOT_A_STRING => $this->getMessage(IsString::NOT_A_STRING),
            ],
        ];

        $this->assertEquals($expected, $result->getMessages());
    }

    public function getInvalidStringValues()
    {
        return [
            [9000],
            [3.14],
            [true],
            [new \stdClass()],
        ];
    }

    private function getMessage($reason)
    {
        $messages = [
            IsString::NOT_A_STRING => 'value must be a string',
        ];

        return $messages[$reason];
    }
}
