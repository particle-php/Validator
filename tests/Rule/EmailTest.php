<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Email;
use Particle\Validator\Validator;

class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
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
        $result = $this->validator->validate(['email' => $value]);
        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getInvalidAddresses
     * @param string $value
     */
    public function testReturnsFalseOnInvalidEmailAddresses($value)
    {
        $this->validator->required('email')->email();
        $result = $this->validator->validate(['email' => $value]);
        $this->assertFalse($result->isValid());
        $expected = [
            'email' => [
                Email::INVALID_FORMAT => 'email must be a valid email address'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
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
            ['berry+plus-sign@github.com.museum'],
            ['berry@githüb.com'],
            ['bërry@github.com']
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
}
