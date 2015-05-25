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

    public function testReturnsFalseIfInvalid()
    {
        $this->validator->required('first_name')->lengthBetween(3, 6);
        $result = $this->validator->validate(['first_name' => 'ad']);

        $this->assertFalse($result);

        $expected = [
            'first_name' => [
                LengthBetween::TOO_SHORT => $this->getMessage(LengthBetween::TOO_SHORT)
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());

        $result = $this->validator->validate(['first_name' => 'Richard']);

        $this->assertFalse($result);
        $expected = [
            'first_name' => [
                LengthBetween::TOO_LONG => $this->getMessage(LengthBetween::TOO_LONG)
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function getMessage($reason)
    {
        $messages = [
            LengthBetween::TOO_SHORT => 'first name must be longer than 3 characters',
            LengthBetween::TOO_LONG => 'first name must be shorter than 6 characters',
        ];

        return $messages[$reason];
    }
}
