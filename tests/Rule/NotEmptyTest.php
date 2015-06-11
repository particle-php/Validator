<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\NotEmpty;
use Particle\Validator\Rule\Required;
use Particle\Validator\Validator;

class NotEmptyTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider notEmptyValues
     */
    public function testReturnsTrueOnNonEmptyValues($value)
    {
        $this->validator->optional('foo', 'foo', false);
        $result = $this->validator->isValid(['foo' => $value]);

        $this->assertTrue($result);
    }

    /**
     * @dataProvider emptyValues
     */
    public function testReturnsFalseOnEmptyValues($value)
    {
        $this->validator->optional('foo', 'foo', false);
        $result = $this->validator->isValid(['foo' => $value]);

        $this->assertFalse($result);
        $this->assertArrayHasKey(NotEmpty::EMPTY_VALUE, $this->validator->getMessages()['foo']);
    }

    public function testBreaksChainOnAllowedEmptyValues()
    {
        $this->validator->required('foo', 'foo', true)->length(5);

        $result = $this->validator->isValid([
            'foo' => null,
        ]);

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testAllowEmptyCanBeConditional()
    {
        $this->validator->required('first_name', 'first name', true)->allowEmpty(function ($values) {
            return $values['foo'] !== 'bar';
        });

        $result = $this->validator->isValid(['foo' => 'bar', 'first_name' => '']);

        $this->assertFalse($result);
        $this->assertEquals(
            [
                'first_name' => [
                    NotEmpty::EMPTY_VALUE => 'first name must not be empty'
                ]
            ],
            $this->validator->getMessages()
        );

        $result = $this->validator->isValid(['foo' => 'not bar!', 'first_name' => '']);
        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }


    public function notEmptyValues()
    {
        return [
            [false],
            [true],
            ['string'],
            [0],
            [0.00]
        ];
    }

    public function emptyValues()
    {
        return [
            [null],
            ['']
        ];
    }
}
