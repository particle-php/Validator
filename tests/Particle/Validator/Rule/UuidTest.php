<?php
use Particle\Validator\Validator;
use Particle\Validator\Rule\Uuid;

class UuidV4Test extends PHPUnit_Framework_TestCase
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
        $this->assertTrue($this->validator->validate(['guid' => '44c0ffee-988a-49dc-0bad-a55c0de2d1e4']));
        $this->assertEquals([], $this->validator->getMessages());
    }

    public function testReturnsFalseOnNoMatch()
    {
        $this->validator->required('guid')->uuid();
        $this->assertFalse($this->validator->validate(['guid' => 'xxc0ffee-988a-49dc-0bad-a55c0de2d1e4']));

        $expected = [
            'guid' => [
                Uuid::INVALID_UUID => 'The value of "guid" must be a valid UUID (v4)'
            ]
        ];

        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testThrowsExceptionOnUnknownVersion()
    {
        $this->setExpectedException('\InvalidArgumentException', 'Unknown UUID version "2"');
        $this->validator->required('guid')->uuid(2);
    }
}
