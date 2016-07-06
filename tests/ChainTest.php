<?php
namespace Particle\Validator\Tests;

use Particle\Validator\Tests\Support\CustomRule;
use Particle\Validator\Validator;

class ChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testCanMountRulesOnChain()
    {
        $rule = new CustomRule();

        $this->validator->required('foo')->mount($rule);

        $result = $this->validator->validate(['foo' => 'not bar']);

        $expected = [
            'foo' => [
                CustomRule::NOT_BAR => 'foo must be equal to "bar"'
            ]
        ];

        $this->assertFalse($result->isValid());
        $this->assertEquals($expected, $result->getMessages());
    }
}
