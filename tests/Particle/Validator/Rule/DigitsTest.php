<?php
use Particle\Validator\Rule\Digits;
use Particle\Validator\Validator;

class DigitsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueOnOnlyDigitCharacters()
    {
        $this->validator->required('digits')->digits();
        $this->assertTrue($this->validator->validate(['digits' => '123456789']));
    }

    /**
     * @dataProvider getNotOnlyDigitValues
     * @param string $value
     */
    public function testReturnsFalseOnNonDigitCharacters($value)
    {
        $this->validator->required('digits')->digits();
        $this->assertFalse($this->validator->validate(['digits' => $value]));

        $expected = [
            'digits' => [
                Digits::NOT_DIGITS => $this->getMessage(Digits::NOT_DIGITS)
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function getNotOnlyDigitValues()
    {
        return [
            ['133.7'],
            ['a1211'],
            ['-12'],
        ];
    }

    public function getMessage($reason)
    {
        $messages = [
            Digits::NOT_DIGITS => 'digits may only consist out of digits'
        ];

        return $messages[$reason];
    }
}
