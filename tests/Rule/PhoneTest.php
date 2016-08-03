<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Phone;
use Particle\Validator\Validator;

class PhoneTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getValidPhoneNumbers
     * @param string $value
     * @param string $countryCode
     */
    public function testReturnsTrueOnValidPhoneNumbers($value, $countryCode)
    {
        $this->validator->required('phone')->phone($countryCode);
        $result = $this->validator->validate(['phone' => $value]);
        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getInvalidPhoneNumberFormats
     * @param string $value
     * @param string $countryCode
     */
    public function testReturnsFalseOnInvalidPhoneNumberFormats($value, $countryCode)
    {
        $this->validator->required('phone')->phone($countryCode);
        $result = $this->validator->validate(['phone' => $value]);
        $this->assertFalse($result->isValid());
        $expected = [
            'phone' => [
                Phone::INVALID_FORMAT => 'phone must have a valid phone number format'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * @dataProvider getInvalidPhoneNumberValues
     * @param string $value
     * @param string $countryCode
     */
    public function testReturnsFalseOnInvalidPhoneNumberValues($value, $countryCode)
    {
        $this->validator->required('phone')->phone($countryCode);
        $result = $this->validator->validate(['phone' => $value]);
        $this->assertFalse($result->isValid());
        $expected = [
            'phone' => [
                Phone::INVALID_VALUE => 'phone must be a valid phone number'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * Returns a list of phone numbers considered valid.
     *
     * @return array
     */
    public function getValidPhoneNumbers()
    {
        return [
            ['044 668 18 00', 'CH'],
            ['91-40-66605792', 'IN'],
            ['(305) 634-5000', 'US'],
            ['202-456-1111', 'US'],
            ['3-6733-3062', 'JP'],
            ['11-3675-3801', 'BR'],
        ];
    }

    /**
     * Returns a list of phone numbers with invalid formats.
     *
     * @return array
     */
    public function getInvalidPhoneNumberFormats()
    {
        return [
            ['abcdef', 'CH'],
            ['12345678', 'XX'],
            ['123_456_7890', 'US'],
        ];
    }

    /**
     * Returns a list of phone numbers considered invalid.
     *
     * @return array
     */
    public function getInvalidPhoneNumberValues()
    {
        return [
            ['1-678-739-9393', 'CH'],
            ['044 668 18 00', 'US'],
            ['+55-11-3675-3801', 'JP'],
            ['3-6733-3062', 'US'],
        ];
    }
}
