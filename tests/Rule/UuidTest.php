<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Validator;
use Particle\Validator\Rule\Uuid;

class UuidV4Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueWhenMatchesUuidV4()
    {
        $this->validator->required('guid')->uuid();
        $result = $this->validator->validate(['guid' => '44c0ffee-988a-49dc-0bad-a55c0de2d1e4']);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    public function testReturnsFalseOnNoMatch()
    {
        $this->validator->required('guid')->uuid();
        $result = $this->validator->validate(['guid' => 'xxc0ffee-988a-49dc-0bad-a55c0de2d1e4']);
        $this->assertFalse($result->isValid());

        $expected = [
            'guid' => [
                Uuid::INVALID_UUID => 'guid must be a valid UUID (v4)'
            ]
        ];

        $this->assertEquals($expected, $result->getMessages());
    }

    public function testThrowsExceptionOnUnknownVersion()
    {
        $this->setExpectedException('\InvalidArgumentException', 'Unknown UUID version "2"');
        $this->validator->required('guid')->uuid(2);
    }
}
