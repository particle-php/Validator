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

        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    public function testContextCanCopyRulesFromOtherContext()
    {
        $this->validator->context('insert', function (Validator $context) {
            $context->required('first_name')->length(5);
        });

        $this->validator->context('update', function (Validator $context) {
            $context->copyContext('insert');
        });

        $result = $this->validator->validate(['first_name' => 'Rick'], 'update');
        $this->assertFalse($result->isValid());
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

        $result = $this->validator->validate([], 'update');
        $this->assertTrue($result->isValid());
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

        $result = $this->validator->validate([], 'insert');
        $this->assertFalse($result->isValid());
    }
}
