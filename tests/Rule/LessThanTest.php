<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\LessThan;
use Particle\Validator\Validator;

class LessThanTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueForValuesLessThanMax()
    {
        $this->validator->required('number')->lessThan(5);
        $result = $this->validator->validate(['number' => 1]);

        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    public function testValidatesExclusiveByDefault()
    {
        $this->validator->required('number')->lessThan(5);
        $result = $this->validator->validate(['number' => 5]);

        $expected = [
            'number' => [
                LessThan::NOT_LESS_THAN => 'number must be less than 5'
            ]
        ];

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    public function testReturnsFalseForValuesGreaterThanMax()
    {
        $this->validator->required('number')->lessThan(5);
        $result = $this->validator->validate(['number' => 10]);

        $expected = [
            'number' => [
                LessThan::NOT_LESS_THAN => $this->getMessage(LessThan::NOT_LESS_THAN),
            ],
        ];

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    private function getMessage($reason)
    {
        $messages = [
            LessThan::NOT_LESS_THAN => 'number must be less than 5',
        ];

        return $messages[$reason];
    }
}
