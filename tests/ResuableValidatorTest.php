<?php
namespace Particle\Validator\Tests;

use Particle\Validator\Validator;

class ReusableValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function configureValidator()
    {
        $this->validator = new Validator();

        $this->validator->required('customerNr')->alnum();
        $this->validator->required('paymentType')->inArray([
            'downPaymentChangeBankAccount',
            'downPaymentHighSpend',
        ]);
        $this->validator->required('reference')->alnum();
        $this->validator->required('description')->lengthBetween(1, 250);
        $this->validator->required('amountPaid')->numeric()->between(0.01, 10000);
        $this->validator->required('notificationDate')->datetime('Ymd');
    }

    public function testCanUseNewValidatorEveryTime()
    {
        foreach ($this->getTestData() as $rowKey => $testRow) {
            $this->configureValidator();

            $isValid = $this->validator->validate($testRow['data']);

            $this->assertEquals($testRow['valid'], $isValid);
            $this->assertEquals($testRow['messages'], $this->validator->getMessages());
        }
    }

    public function testCanReuseValidatorMultipleTimes()
    {
        $this->configureValidator();

        foreach ($this->getTestData() as $rowKey => $testRow) {
            $isValid = $this->validator->validate($testRow['data']);
            $this->assertEquals($testRow['valid'], $isValid);
            $this->assertEquals($testRow['messages'], $this->validator->getMessages());
        }
    }

    private function getTestData()
    {
        return [
            [
                'data' => [
                    'customerNr' => 'c000012340',
                    'paymentType' => '',
                    'reference' => '',
                    'description' => 'Test payment 1',
                    'amountPaid' => 1000.00,
                    'notificationDate' => '20150401',
                ],
                'valid' => false,
                'messages' => [
                    'paymentType' => [
                        'NotEmpty::EMPTY_VALUE' => 'paymentType must not be empty',
                    ],
                    'reference' => [
                        'NotEmpty::EMPTY_VALUE' => 'reference must not be empty',
                    ],
                ],
            ],

            [
                'data' => [
                    'customerNr' => 'c000001234',
                    'paymentType' => 'gfh',
                    'reference' => 'dfg',
                    'description' => 'Test payment 2',
                    'amountPaid' => 25.00,
                    'notificationDate' => '20150401',
                ],
                'valid' => false,
                'messages' => [
                    'paymentType' => [
                        'InArray::NOT_IN_ARRAY' => 'paymentType must be in the defined set of values',
                    ],
                ],
            ],
            [
                'data' => [
                    'customerNr' => 'c000005678',
                    'paymentType' => 'sdf ',
                    'reference' => '',
                    'description' => 'Test payment 3',
                    'amountPaid' => 25.00,
                    'notificationDate' => '20150401',
                ],
                'valid' => false,
                'messages' => [
                    'paymentType' => [
                        'InArray::NOT_IN_ARRAY' => 'paymentType must be in the defined set of values',
                    ],
                    'reference' => [
                        'NotEmpty::EMPTY_VALUE' => 'reference must not be empty',
                    ],
                ],
            ],
        ];
    }
}
