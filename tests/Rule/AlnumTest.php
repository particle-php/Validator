<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Alnum;
use Particle\Validator\Validator;

class AlnumTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getAlphanumericWithoutSpaces
     * @param $value
     */
    public function testReturnsTrueForValidValues($value)
    {
        $this->validator->required('first_name')->alnum();
        $result = $this->validator->validate(['first_name' => $value]);

        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider getAlphanumericWithSpaces
     * @param $value
     */
    public function testReturnsTrueForValidValuesWithSpaces($value)
    {
        $this->validator->required('first_name')->alnum(true);
        $result = $this->validator->validate(['first_name' => $value]);

        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider getAlphanumericWithAccents
     * @param $value
     */
    public function testReturnsTrueForDifferentAlphabets($value)
    {
        $this->validator->required('first_name')->alnum(true);
        $result = $this->validator->validate(['first_name' => $value]);

        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider getAlphanumericWithSpaces
     * @param $value
     */
    public function testReturnsFalseForValuesWithSpaces($value, $errorReason)
    {
        $this->validator->required('first_name')->alnum();
        $result = $this->validator->validate(['first_name' => $value]);

        $expected = ['first_name' => [$errorReason => $this->getMessage($errorReason)]];

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }

    public function getAlphanumericWithoutSpaces()
    {
        return [
            ['alphanumeric1337'],
            ['1337alphanumerictoo']
        ];
    }

    public function getAlphanumericWithSpaces()
    {
        return [
            ['alphanumeric 1337', Alnum::NOT_ALNUM],
            ['1337 this is alpha numeric', Alnum::NOT_ALNUM]
        ];
    }

    public function getAlphanumericWithAccents()
    {
        return [
            ['Björk']
        ];
    }

    public function getMessage($reason)
    {
        $messages = [
            Alnum::NOT_ALNUM => 'first name may only consist out of numeric and alphabetic characters'
        ];

        return $messages[$reason];
    }
}
