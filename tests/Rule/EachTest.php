<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Boolean;
use Particle\Validator\Rule\Each;
use Particle\Validator\Rule\Required;
use Particle\Validator\Validator;

class EachTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testCanValidateNestedRepeatingArrays()
    {
        $data = [
            'invoices' => [
                1 => [
                    'id' => 1,
                    'date' => '2015-10-09',
                    'lines' => [
                        ['description' => 'first line'],
                        ['description' => 'second line'],
                    ]
                ],
                2 => [
                    'id' => 2,
                    'date' => '2015-10-09',
                    'lines' => [
                        ['description' => 'first line'],
                    ]
                ],
            ]
        ];

        $this->validator->required('invoices')->each(function (Validator $invoiceValidator) {
            $invoiceValidator->required('id')->integer();
            $invoiceValidator->required('date')->datetime('Y-m-d');

            $invoiceValidator->required('lines')->each(function (Validator $lineValidator) {
                $lineValidator->required('description')->lengthBetween(0, 20);
            });
        });

        $result = $this->validator->validate($data);

        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    public function testReturnsErrorOnNonArray()
    {
        $this->validator->required('foo')->each(function (Validator $validator) {
            $validator->required('bar')->bool();
        });

        $result = $this->validator->validate([
            'foo' => 1
        ]);

        $this->assertFalse($result->isValid());

        $expected = [
            'foo' => [
                Each::NOT_AN_ARRAY => 'foo must be an array',
            ]
        ];

        $this->assertEquals($expected, $result->getMessages());
    }

    public function testReturnsErrorOnNonArrayItem()
    {
        $this->validator->required('foo')->each(function (Validator $validator) {
            $validator->required('bar')->bool();
        });

        $result = $this->validator->validate([
            'foo' => [
                'bar' => 1,
            ],
        ]);

        $this->assertFalse($result->isValid());
    }

    public function testCanValidateNestedArrays()
    {
        $this->validator->required('foo')->each(function (Validator $validator) {
            $validator->required('bar')->bool();
        });

        $result = $this->validator->validate([
            'foo' => [
                ['bar' => true],
                ['bar' => true],
                ['bar' => false],
                [],
            ]
        ]);

        $this->assertFalse($result->isValid());

        $expected = [
            'foo.3.bar' => [
                Required::NON_EXISTENT_KEY => 'bar must be provided, but does not exist'
            ]
        ];

        $this->assertEquals($expected, $result->getMessages());
    }

    public function testCanUseOverwrittenMessagesWithParameters()
    {
        $this->validator->required('foo')->each(function (Validator $validator) {
            $validator->required('bar')->bool();
        });

        $this->validator->overwriteDefaultMessages([
            Boolean::NOT_BOOL => 'Not a valid bool, {{ key }}'
        ]);

        $result = $this->validator->validate([
            'foo' => [
                'first' => [
                    'bar' => 'certainly not a bool'
                ]
            ]
        ]);

        $this->assertFalse($result->isValid());

        $expected = [
            'foo.first.bar' => [
                Boolean::NOT_BOOL => 'Not a valid bool, bar'
            ]
        ];

        $this->assertEquals($expected, $result->getMessages());
    }

    public function testWillAppendValuesToOutput()
    {
        $this->validator->required('foo')->each(function (Validator $validator) {
            $validator->required('bar')->bool();
        });

        $values = [
            'foo' => [
                'first' => [
                    'bar' => true,
                ]
            ]
        ];

        $result = $this->validator->validate($values);

        $this->assertTrue($result->isValid());
        $this->assertEquals($values, $result->getValues());
    }
}
