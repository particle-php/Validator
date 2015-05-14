<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\LengthBetween;
use Particle\Validator\Validator;

class LengthBetweenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueIfLengthIsExactlyMinOrMax()
    {
        $this->validator->required('first_name')->lengthBetween(2, 7);
        $this->assertTrue($this->validator->validate(['first_name' => 'ad']));
        $this->assertTrue($this->validator->validate(['first_name' => 'Richard']));
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testReturnsTrueIfMaxIsNull()
    {
        $this->validator->required('password')->lengthBetween(2, null);
        $this->assertTrue($this->validator->validate(['password' => str_repeat('foo', 100)]));
        $this->assertEquals([], $this->validator->getMessages());
    }

    /**
     * @dataProvider getValues
     * @param $value
     * @param $error
     */
    public function testReturnsFalseIfLengthIsExactlyMinOrMaxAndRuleIsExclusive($value, $error)
    {
        $this->validator->required('first_name')->lengthBetween(2, 7, false);
        $this->assertFalse($this->validator->validate(['first_name' => $value]));
        $expected = [
            'first_name' => [
                $error => $this->getMessage($error)
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function getMessage($reason)
    {
        $messages = [
            LengthBetween::TOO_SHORT => 'The length of "first name" is too short, must be longer than 2 characters',
            LengthBetween::TOO_LONG => 'The length of "first name" is too long, must be shorter than 7 characters',
        ];

        return $messages[$reason];
    }

    public function getValues()
    {
        return [
            ['Ad', LengthBetween::TOO_SHORT],
            ['Richard', LengthBetween::TOO_LONG]
        ];
    }
}
