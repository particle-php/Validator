<?php
namespace Particle\Validator\Tests;

use Particle\Validator\Rule;
use Particle\Validator\Value\Container;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    public function testUsesMessageStack()
    {
        $length = new Rule\Length(5);
        $length->setParameters('key', 'name');

        $ms = $this->getMock('Particle\Validator\MessageStack', ['append']);
        $ms->expects($this->once())->method('append')->with(
            'key',
            Rule\Length::TOO_SHORT,
            '{{ name }} is too short and must be {{ length }} characters long',
            [
                'key' => 'key',
                'name' => 'name',
                'length' => 5
            ]
        );

        $length->setMessageStack($ms);

        $this->assertFalse($length->isValid('first_name', new Container(['first_name' => ''])));
    }
    
    /**
     * test for issue #83
     * was triggering fatal error if failling rule didnt have a messagestack
     */
    public function testRuleWithoutMessageStack()
    {
        $length = new \Particle\Validator\Rule\Length(5);
        $this->assertFalse($length->validate("four"));
    }
}
