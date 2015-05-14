<?php
namespace Particle\Tests;

use Particle\Validator\Chain;
use Particle\Validator\Rule;
use Particle\Validator\Validator;

class ContextTest extends \PHPUnit_Framework_TestCase
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
        $this->validator->context('insert', function (Validator $context) {
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

            $context->overwriteMessages([
                'first_name' => [
                    Rule\Length::TOO_SHORT => 'This is from inside the context.'
                ]
            ]);
        });

        $this->validator->overwriteMessages([
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

    public function testMessagesWillBeInheritedFromDefaultContext()
    {
        $this->validator->context('insert', function (Validator $context) {
            $context->required('first_name')->length(5);
        });

        $this->validator->overwriteMessages([
            'first_name' => [
                Rule\Length::TOO_SHORT => 'This is outside of the context',
            ]
        ]);

        $this->validator->validate(['first_name' => 'Rick'], 'insert');

        $expected = [
            'first_name' => [
                Rule\Length::TOO_SHORT => 'This is outside of the context'
            ]
        ];

        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testContextCanCopyRulesFromOtherContext()
    {
        $this->validator->context('insert', function (Validator $context) {
            $context->overwriteMessages([
                'first_name' => [
                    Rule\Length::TOO_SHORT => 'From inside the "insert" context.'
                ]
            ]);

            $context->required('first_name')->length(5);
        });

        $this->validator->context('update', function (Validator $context) {
            $context->copyContext('insert');
        });

        $this->assertFalse($this->validator->validate(['first_name' => 'Rick'], 'update'));

        $expected = [
            'first_name' => [
                Rule\Length::TOO_SHORT => 'From inside the "insert" context.'
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testContextCopyCanAlterChains()
    {
        $this->validator->context('insert', function (Validator $context) {
            $context->required('first_name')->length(5);
        });

        $this->validator->context('update', function (Validator $context) {
            $context->copyContext('insert', function (array $chains) {
                /** @var Chain $chain */
                foreach ($chains as $chain) {
                    $chain->required(function () {
                        return false; // all fields optional.
                    });
                }
            });
        });

        $this->assertTrue($this->validator->validate([], 'update'));
    }

    public function testContextCopyClonesButDoesNotOverwrite()
    {
        $this->validator->context('insert', function (Validator $context) {
            $context->required('first_name')->length(5);
        });

        $this->validator->context('update', function (Validator $context) {
            $context->copyContext('insert', function (array $chains) {
                /** @var Chain $chain */
                foreach ($chains as $chain) {
                    $chain->required(function () {
                        return false; // all fields optional.
                    });
                }
            });
        });

        $this->assertFalse($this->validator->validate([], 'insert'));
    }
}
