<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Validator;

class RequiredTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsFalseOnUnsetRequiredValue()
    {
        $this->validator->required('foo');

        $result = $this->validator->validate([
        ]);

        $this->assertFalse($result);
    }

    public function testReturnsTrueOnSetRequiredValues()
    {
        $this->validator->required('foo');
        $result = $this->validator->validate([
            'foo' => 'bar'
        ]);
        $this->assertTrue($result);
    }

    public function testReturnsTrueOnNullValue()
    {
        $this->validator->required('foo', 'foo', true);
        $result = $this->validator->validate([
            'foo' => null
        ]);
        $this->assertTrue($result);
    }

    public function testReturnsTrueAndBreaksOnRequiredButNullValue()
    {
        $this->validator->required('foo', 'foo', true)->callback(function($value) {
            return false; // always false!
        });

        $result = $this->validator->validate([
            'foo' => null,
        ]);

        $this->assertTrue($result);
    }
}
