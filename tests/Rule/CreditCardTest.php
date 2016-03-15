<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\CreditCard;
use Particle\Validator\Validator;

class CreditCardTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getValidCreditCardNumbers
     * @param string $value
     */
    public function testReturnsTrueOnValidCreditCardNumbers($value)
    {
        $this->validator->required('creditCard')->creditCard();
        $result = $this->validator->validate(['creditCard' => $value]);
        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getInvalidCreditCardNumberFormats
     * @param string $value
     */
    public function testReturnsFalseOnInvalidCreditCardNumberFormats($value)
    {
        $this->validator->required('creditCard')->allowEmpty(true)->creditCard();
        $result = $this->validator->validate(['creditCard' => $value]);
        $this->assertFalse($result->isValid());
        $expected = [
            'creditCard' => [
                CreditCard::INVALID_FORMAT => 'creditCard must have a valid credit card number format'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * @dataProvider getInvalidCreditCardNumberChecksums
     * @param string $value
     */
    public function testReturnsFalseOnInvalidCreditCardNumberChecksums($value)
    {
        $this->validator->required('creditCard')->allowEmpty(true)->creditCard();
        $result = $this->validator->validate(['creditCard' => $value]);
        $this->assertFalse($result->isValid());
        $expected = [
            'creditCard' => [
                CreditCard::INVALID_CHECKSUM => 'creditCard must be a valid credit card number'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * Returns a list of credit card numbers considered valid.
     *
     * @return array
     */
    public function getValidCreditCardNumbers()
    {
        return [
            ['4532815084485002'],
            ['4556723436104480'],
            ['5162235100375901'],
            ['5493306520818000'],
            ['6011796537313931'],
            ['6011748546876911'],
            ['373297793578767'],
            ['345960056018590'],
        ];
    }

    /**
     * Returns a list of credit card numbers with invalid formats.
     *
     * @return array
     */
    public function getInvalidCreditCardNumberFormats()
    {
        return [
            ['4532815O84485002'],
            ['516223510037590'],
            [' 601179653731392'],
        ];
    }

    /**
     * Returns a list of credit card numbers with invalid checksums.
     *
     * @return array
     */
    public function getInvalidCreditCardNumberChecksums()
    {
        return [
            ['4532815084485001'],
            ['5162235100375991'],
            ['6011796537313831'],
            ['373297793578667'],
        ];
    }
}
