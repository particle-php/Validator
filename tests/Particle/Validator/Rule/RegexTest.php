<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\Regex;
use Particle\Validator\Validator;

class RegexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueWhenMatchesRegex()
    {
        $this->validator->required('first_name')->regex('/^berry$/i');
        $this->assertTrue($this->validator->validate(['first_name' => 'Berry']));
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testReturnsFalseOnNoMatch()
    {
        $this->validator->required('first_name')->regex('~this wont match~');
        $this->assertFalse($this->validator->validate(['first_name' => 'Berry']));
        $expected = [
            'first_name' => [
                Regex::NO_MATCH => 'The value of "first name" is invalid'
            ]
        ];

        $this->assertEquals($expected, $this->validator->getMessages());
    }
}
