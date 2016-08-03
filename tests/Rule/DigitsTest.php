<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Digits;
use Particle\Validator\Validator;

class DigitsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueOnOnlyDigitCharacters()
    {
        $this->validator->required('digits')->digits();
        $result = $this->validator->validate(['digits' => '123456789']);
        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getNotOnlyDigitValues
     * @param string $value
     */
    public function testReturnsFalseOnNonDigitCharacters($value)
    {
        $this->validator->required('digits')->digits();
        $result = $this->validator->validate(['digits' => $value]);
        $this->assertFalse($result->isValid());

        $expected = [
            'digits' => [
                Digits::NOT_DIGITS => $this->getMessage(Digits::NOT_DIGITS)
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
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
