<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\Digits;
use Particle\Validator\Rule\Email;
use Particle\Validator\Validator;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    /**
     * @dataProvider getValidAddresses
     * @param string $value
     */
    public function testReturnsTrueOnValidEmailaddresses($value)
    {
        $this->validator->required('email')->email();
        $this->assertTrue($this->validator->validate(['email' => $value]));
    }

    /**
     * @dataProvider getInvalidAddresses
     * @param string $value
     */
    public function testReturnsFalseOnInvalidEmailaddresses($value)
    {
        $this->validator->required('email')->email();
        $this->assertFalse($this->validator->validate(['email' => $value]));
        $expected = [
            'email' => [
                Email::INVALID_FORMAT => 'The value of "email" must be a valid email address'
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    /**
     * Returns a list of addresses considered valid.
     *
     * @return array
     */
    public function getValidAddresses()
    {
        return [
            ['berry@github.com'],
            ['berry+plus-sign@github.com.museum']
        ];
    }

    /**
     * Returns a list of addresses considered invalid.
     *
     * @return array
     */
    public function getInvalidAddresses()
    {
        return [
            ['berry'],
            ['not valid@"not valid"']
        ];
    }


    public function getMessage($reason)
    {
        $messages = [
            Digits::NOT_DIGITS => 'The value of "digits" must consist only out of digits'
        ];

        return $messages[$reason];
    }
}
