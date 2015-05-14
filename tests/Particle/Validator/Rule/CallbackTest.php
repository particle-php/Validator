<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Exception\InvalidValueException;
use Particle\Validator\Rule\Callback;
use Particle\Validator\Validator;

class CallbackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueWhenCallbackReturnsTrue()
    {
        $this->validator->required('first_name')->callback(function ($value) {
            return $value === 'berry';
        });

        $result = $this->validator->validate(['first_name' => 'berry']);
        $this->assertTrue($result);
    }

    public function testReturnsFalseAndLogsErrorWhenCallbackReturnsFalse()
    {
        $this->validator->required('first_name')->callback(function ($value) {
            return $value !== 'berry';
        });

        $result = $this->validator->validate(['first_name' => 'berry']);
        $this->assertFalse($result);

        $expected = [
            'first_name' => [
                Callback::INVALID_VALUE => $this->getMessage(Callback::INVALID_VALUE)
            ]
        ];

        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testCanLogDifferentErrorMessageByThrowingException()
    {
        $this->validator->required('first_name')->callback(function ($value) {
            if ($value !== 'berry') {
                throw new InvalidValueException(
                    'This is my error',
                    'Callback::CUSTOM'
                );
            }
            return true;
        });

        $result = $this->validator->validate(['first_name' => 'bill']);
        $this->assertFalse($result);

        $expected = [
            'first_name' => [
                'Callback::CUSTOM' => 'This is my error'
            ]
        ];

        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testCanReadTheContextOfValidation()
    {
        $this->validator->required('first_name')->callback(function ($value, $context) {
            return $context['last_name'] === 'Langerak' && $value === 'Berry';
        });

        $result = $this->validator->validate(['first_name' => 'Berry', 'last_name' => 'Langerak']);
        $this->assertTrue($result);
    }

    public function getMessage($reason)
    {
        $messages = [
            Callback::INVALID_VALUE => 'The value of "first name" is invalid'
        ];

        return $messages[$reason];
    }
}
