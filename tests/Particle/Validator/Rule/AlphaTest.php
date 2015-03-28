<?php
use Particle\Validator\Rule\Alpha;
use Particle\Validator\Validator;

class AlphaTest extends PHPUnit_Framework_TestCase
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
     * @dataProvider getAlphaWithoutSpaces
     * @param $value
     */
    public function testReturnsTrueForValidValues($value)
    {
        $this->validator->required('first_name')->alpha();
        $result = $this->validator->validate(['first_name' => $value]);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    /**
     * @dataProvider getAlphaWithSpaces
     * @param $value
     */
    public function testReturnsTrueForValidValuesWithSpaces($value)
    {
        $this->validator->required('first_name')->alpha(true);
        $result = $this->validator->validate(['first_name' => $value]);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    /**
     * @dataProvider getAlphaWithAccents
     * @param $value
     */
    public function testReturnsTrueForDifferentAlphabets($value)
    {
        $this->validator->required('first_name')->alpha(true);
        $result = $this->validator->validate(['first_name' => $value]);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    /**
     * @dataProvider getAlphaWithSpaces
     * @param $value
     */
    public function testReturnsFalseForValuesWithSpaces($value, $errorReason)
    {
        $this->validator->required('first_name')->alpha();
        $result = $this->validator->validate(['first_name' => $value]);

        $expected = ['first_name' => [$errorReason => $this->getMessage($errorReason)]];

        $this->assertFalse($result);
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function getAlphaWithoutSpaces()
    {
        return [
            ['onlyalphabet'],
            ['alphabetagamma']
        ];
    }

    public function getAlphaWithSpaces()
    {
        return [
            ['alpha checks for alphabetical characters', Alpha::NOT_ALPHA],
            ['this is alpha numeric too', Alpha::NOT_ALPHA]
        ];
    }

    public function getAlphaWithAccents()
    {
        return [
            ['Björk']
        ];
    }

    public function getMessage($reason)
    {
        $messages = [
            Alpha::NOT_ALPHA => 'The value of "first name" can only consist out of alphabetic characters',
        ];

        return $messages[$reason];
    }
}
