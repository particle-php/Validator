<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Length;
use Particle\Validator\Validator;

class LengthTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getInvalidValues
     * @param $value
     * @param $error
     */
    public function testInvalidValuesWillReturnFalseAndLogError($value, $error)
    {
        $this->validator->required('first_name')->length(5, 'UTF-8');
        $result = $this->validator->validate(['first_name' => $value]);

        $expected = ['first_name' => [$error => $this->getMessage($error)]];
        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * @dataProvider getValidValues
     * @param $value
     */
    public function testValidValuesWillReturnTrue($value)
    {
        $this->validator->required('first_name')->length(5, 'UTF-8');
        $result = $this->validator->validate(['first_name' => $value]);
        $this->assertTrue($result->isValid());
    }

    public function getInvalidValues()
    {
        return [
            ['rick', Length::TOO_SHORT],
            ['hendrik', Length::TOO_LONG],
            ['loïc', Length::TOO_SHORT], // UTF-8 multibyte test
        ];
    }

    public function getValidValues()
    {
        return [
            ['berry'],
            [12345], // integers are cast to strings
            ['björk'], // UTF-8 multibyte test
        ];
    }

    private function getMessage($reason)
    {
        $messages = [
            Length::TOO_SHORT => 'first name is too short and must be 5 characters long',
            Length::TOO_LONG => 'first name is too long and must be 5 characters long',
        ];
        return $messages[$reason];
    }
}
