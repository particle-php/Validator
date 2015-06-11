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
        $result = $this->validator->validate(['first_name' => 'Berry']);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    public function testReturnsFalseOnNoMatch()
    {
        $this->validator->required('first_name')->regex('~this wont match~');
        $result = $this->validator->validate(['first_name' => 'Berry']);
        $this->assertFalse($result->isValid());
        $expected = [
            'first_name' => [
                Regex::NO_MATCH => 'first name is invalid'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }
}
