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
        $result = $this->validator->isValid(['number' => 5]);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testValidatesInclusiveByDefault()
    {
        $this->validator->required('number')->between(1, 10);
        $result = $this->validator->isValid(['number' => 1]);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testReturnsFalseForValuesNotBetweenMinAndMaxLowerError()
    {
        $this->validator->required('number')->between(1, 10);
        $result = $this->validator->isValid(['number' => 0]);

        $expected = [
            'number' => [
                Between::TOO_SMALL => $this->getMessage(Between::TOO_SMALL)
            ]
        ];
        $this->assertFalse($result);
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testReturnsFalseForValuesNotBetweenMinAndMaxUpperError()
    {
        $this->validator->required('number')->between(1, 10);
        $result = $this->validator->isValid(['number' => 11]);

        $expected = [
            'number' => [
                Between::TOO_BIG => $this->getMessage(Between::TOO_BIG)
            ]
        ];
        $this->assertFalse($result);
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function getMessage($reason)
    {
        $messages = [
            Between::TOO_SMALL => 'number must be greater than or equal to 1',
            Between::TOO_BIG => 'number must be less than or equal to 10',
        ];

        return $messages[$reason];
    }
}
