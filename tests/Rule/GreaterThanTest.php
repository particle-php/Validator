<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\GreaterThan;
use Particle\Validator\Validator;

class GreaterThanTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueForValuesGreaterThanMin()
    {
        $this->validator->required('number')->greaterThan(1);
        $result = $this->validator->validate(['number' => 5]);

        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    public function testValidatesExclusiveByDefault()
    {
        $this->validator->required('number')->greaterThan(1);
        $result = $this->validator->validate(['number' => 1]);

        $expected = [
            'number' => [
                GreaterThan::NOT_GREATER_THAN => 'number must be greater than 1'
            ]
        ];

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    public function testReturnsFalseForValuesLessThanMin()
    {
        $this->validator->required('number')->greaterThan(1);
        $result = $this->validator->validate(['number' => 0]);

        $expected = [
            'number' => [
                GreaterThan::NOT_GREATER_THAN => $this->getMessage(GreaterThan::NOT_GREATER_THAN),
            ],
        ];

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    private function getMessage($reason)
    {
        $messages = [
            GreaterThan::NOT_GREATER_THAN => 'number must be greater than 1',
        ];

        return $messages[$reason];
    }
}
