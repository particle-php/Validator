<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\Between;
use Particle\Validator\Validator;

class BetweenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueForValuesBetweenMinAndMax()
    {
        $this->validator->required('number')->between(1, 10);
        $result = $this->validator->validate(['number' => 5]);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testValidatesInclusiveByDefault()
    {
        $this->validator->required('number')->between(1, 10);
        $result = $this->validator->validate(['number' => 1]);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testValidatesExclusiveOnRequest()
    {
        $this->validator->required('number')->between(1, 10, false);
        $result = $this->validator->validate(['number' => 1]);

        $expected = [
            'number' => [
                Between::NOT_BETWEEN => $this->getMessage(Between::NOT_BETWEEN)
            ]
        ];
        $this->assertFalse($result);
        $this->assertEquals($expected, $this->validator->getMessages());

        $this->assertTrue($this->validator->validate(['number' => 2]));
        $this->assertFalse($this->validator->validate(['number' => 10]));
    }

    /**
     * @dataProvider getInvalidValues
     * @param $value
     */
    public function testReturnsFalseForValuesNotBetweenMinAndMax($value)
    {
        $this->validator->required('number')->between(1, 10);
        $result = $this->validator->validate(['number' => $value]);

        $expected = [
            'number' => [
                Between::NOT_BETWEEN => $this->getMessage(Between::NOT_BETWEEN)
            ]
        ];
        $this->assertFalse($result);
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function getInvalidValues()
    {
        return [
            [-1, Between::NOT_BETWEEN],
            [11, Between::NOT_BETWEEN]
        ];
    }

    public function getMessage($reason)
    {
        $messages = [
            Between::NOT_BETWEEN => 'number must be between 1 and 10'
        ];

        return $messages[$reason];
    }
}
