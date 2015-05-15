<?php
use Particle\Validator\Rule\Equal;
use Particle\Validator\Validator;

class EqualTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueOnEqualValue()
    {
        $this->validator->required('first_name')->equals('berry');
        $this->assertTrue($this->validator->validate(['first_name' => 'berry']));
    }

    public function testReturnsFalseOnNonEqualValue()
    {
        $this->validator->required('first_name')->equals(0);
        $this->assertFalse($this->validator->validate(['first_name' => '0'])); // strict typing all the way!
        $this->assertFalse($this->validator->validate(['first_name' => 'No cigar, and not even close.']));

        $expected = [
            'first_name' => [
                Equal::NOT_EQUAL => 'first name must be equal to "0"'
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }
}
