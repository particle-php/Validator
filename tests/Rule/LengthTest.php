<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\Length;
use Particle\Validator\Validator;

class LengthTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getInvalidValues
     * @param $value
     * @param $error
     */
    public function testInvalidValuesWillReturnFalseAndLogError($value, $error)
    {
        $this->validator->required('first_name')->length(5);
        $result = $this->validator->isValid(['first_name' => $value]);

        $expected = ['first_name' => [$error => $this->getMessage($error)]];
        $this->assertFalse($result);
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    /**
     * @dataProvider getValidValues
     * @param $value
     */
    public function testValidValuesWillReturnTrue($value)
    {
        $this->validator->required('first_name')->length(5);
        $result = $this->validator->isValid(['first_name' => $value]);

        $this->assertTrue($result);
    }

    public function getInvalidValues()
    {
        return [
            ['rick', Length::TOO_SHORT],
            ['hendrik', Length::TOO_LONG]
        ];
    }

    public function getValidValues()
    {
        return [
            ['berry'],
            [12345] // integers are cast to strings
        ];
    }

    public function getMessage($reason)
    {
        $messages = [
            Length::TOO_SHORT => 'first name is too short and must be 5 characters long',
            Length::TOO_LONG => 'first name is too long and must be 5 characters long',
        ];
        return $messages[$reason];
    }
}
