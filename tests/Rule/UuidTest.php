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

    public function correctUUIDv4()
    {
        return array(
            array('44c0ffee-988a-49dc-8bad-a55c0de2d1e4'),
            array('de305d54-75b4-431b-adb2-eb6b9e546014'),
            array('00000000-0000-4000-8000-000000000000'),
        );
    }

    public function incorrectUUIDv4()
    {
        return array(
            array('xxc0ffee-988a-49dc-8bad-a55c0de2d1e4'),
            array('123e4567-e89b-12d3-a456-426655440000'),
            array('00000000-0000-0000-0000-000000000000'),      // NIL uuid
            array('a8098c1a-f86e-11da-bd1a-00112444be1e'),      // UUIDv1
            array('6fa459ea-ee8a-3ca4-894e-db77e160355e'),      // UUIDv3
            array('886313e1-3b8a-5372-9b90-0c9aee199e5d'),      // UUIDv4
        );
    }

    /**
     * @dataProvider correctUUIDv4
     */
    public function testReturnsTrueWhenMatchesUuidV4($uuid)
    {
        $this->validator->required('guid')->uuid();
        $result = $this->validator->validate(['guid' => $uuid]);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider incorrectUUIDv4
     */
    public function testReturnsFalseOnNoMatch($uuid)
    {
        $this->validator->required('guid')->uuid();
        $result = $this->validator->validate(['guid' => $uuid]);
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
