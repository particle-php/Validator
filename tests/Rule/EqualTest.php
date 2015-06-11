<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\Equal;
use Particle\Validator\Validator;

class EqualTest extends \PHPUnit_Framework_TestCase
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
        $result = $this->validator->validate(['first_name' => 'berry']);
        $this->assertTrue($result->isValid());
    }

    public function testReturnsFalseOnNonEqualValue()
    {
        $this->validator->required('first_name')->equals(0);

        $result = $this->validator->validate(['first_name' => '0']); // strict typing all the way!
        $this->assertFalse($result->isValid());

        $result = $this->validator->validate(['first_name' => 'No cigar, and not even close.']);
        $this->assertFalse($result->isValid());

        $expected = [
            'first_name' => [
                Equal::NOT_EQUAL => 'first name must be equal to "0"'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }
}
