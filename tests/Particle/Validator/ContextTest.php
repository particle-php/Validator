<?php

use Particle\Validator\Context;
use Particle\Validator\Rule;
use Particle\Validator\Validator;

class ContextTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testCanBeValidatedIndependently()
    {
        $this->validator->context('insert', function(Validator $context) {
            $context->required('first_name')->length(5);
        });

        $this->validator->required('first_name')->length(3);

        $result = $this->validator->validate(['first_name' => 'berry'], 'insert');

        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testCanHaveIndependentMessages()
    {
        $this->validator->context('insert', function (Validator $context) {
            $context->required('first_name')->length(5);

            $context->setMessages([
                'first_name' => [
                    Rule\Length::TOO_SHORT => 'This is from inside the context.'
                ]
            ]);
        });

        $this->validator->setMessages([
            'first_name' => [
                Rule\Length::TOO_SHORT => 'This is outside of the context',
            ]
        ]);

        $this->validator->validate(['first_name' => 'Rick'], 'insert');
        $expected = [
            'first_name' => [
                Rule\Length::TOO_SHORT => 'This is from inside the context.'
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }
}
