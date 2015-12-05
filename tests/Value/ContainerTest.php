<?php
namespace Particle\Validator\Tests\Value;

use Particle\Validator\Value\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsValueIfKeyExists()
    {
        $container = new Container();
        $container->set('foo', 'bar');
        $this->assertEquals('bar', $container->get('foo'));
    }

    public function testReturnsNullIfKeyDoesNotExist()
    {
        $container = new Container();
        $this->assertNull($container->get('foobar'));
    }
}
