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

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function correctUUIDFormat()
    {
        return array(
            array('00000000-0000-0000-0000-000000000000'),
            array('05D989B3-A786-E411-80C8-0050568766E4'),
            array('05D989B3-A786-E411-80C8-0050568766E4'),
            array('8672e692-b936-e611-80da-0050568766e4'),
            array('9042c873-ed53-e611-80c6-0050568968d5'),
            array('5c3d167e-6011-11e6-8b77-86f30ca893d3'),
            array('885e561e-6011-11e6-8b77-86f30ca893d3'),
            array('9293b566-6011-11e6-8b77-86f30ca893d3'),
        );
    }

    public function correctUUIDNIL()
    {
        return array(
            array('00000000-0000-0000-0000-000000000000'),
        );
    }

    public function correctUUIDv1()
    {
        return array(
            array('5c3d167e-6011-11e6-8b77-86f30ca893d3'),
            array('885e561e-6011-11e6-8b77-86f30ca893d3'),
            array('9293b566-6011-11e6-8b77-86f30ca893d3'),
        );
    }

    public function correctUUIDv2()
    {
        return array(
            array('5c3d167e-6011-21e6-8b77-86f30ca893d3'),
            array('885e561e-6011-21e6-bb77-86f30ca893d3'),
            array('9293b566-6011-21e6-ab77-86f30ca893d3'),
        );
    }

    public function correctUUIDv3()
    {
        return array(
            array('5C3d167e-6011-31e6-8b77-86f30ca893d3'),
            array('885e561e-6011-31E6-bb77-86f30ca893d3'),
            array('9293b566-6011-31e6-9b77-86f30ca893d3'),
        );
    }

    public function correctUUIDv4()
    {
        return array(
            array('44c0ffee-988a-49dc-8bad-a55c0de2d1e4'),
            array('de305d54-75b4-431b-adb2-eb6b9e546014'),
            array('00000000-0000-4000-8000-000000000000'),
        );
    }

    public function correctUUIDv5()
    {
        return array(
            array('44c0ffee-988a-59dc-8bad-a55c0de2d1e4'),
            array('de305d54-75b4-531b-adb2-eb6b9e546014'),
            array('00000000-0000-5000-8000-000000000000'),
        );
    }

    public function correctUUIDNILv4v5()
    {
        return array_merge($this->correctUUIDNIL(), $this->correctUUIDv4(), $this->correctUUIDv5());
    }

    public function incorrectUUIDv4()
    {
        return array(
            array('xxc0ffee-988a-49dc-8bad-a55c0de2d1e4'),
            array('123e4567-e89b-12d3-a456-426655440000'),
            array('00000000-0000-0000-0000-000000000000'),      // NIL uuid
            array('a8098c1a-f86e-11da-bd1a-00112444be1e'),      // UUIDv1
            array('6fa459ea-ee8a-3ca4-894e-db77e160355e'),      // UUIDv3
            array('886313e1-3b8a-5372-9b90-0c9aee199e5d'),      // UUIDv5
            array('de305d54-75b4-431b-adb2-eb6b9e546014a'),
            array('fde305d54-75b4-431b-adb2-eb6b9e546014'),
        );
    }

    public function incorrectVersionsProvider()
    {
        return [
            [4],
            [Uuid::UUID_V5 * 2],
        ];
    }

    /**
     * @dataProvider correctUUIDNIL
     */
    public function testReturnsTrueWhenMatchesUuidNIL($uuid)
    {
        $this->validator->required('guid')->uuid(Uuid::UUID_NIL);
        $result = $this->validator->validate(['guid' => $uuid]);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider correctUUIDv1
     */
    public function testReturnsTrueWhenMatchesUuidV1($uuid)
    {
        $this->validator->required('guid')->uuid(Uuid::UUID_V1);
        $result = $this->validator->validate(['guid' => $uuid]);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider correctUUIDFormat
     */
    public function testReturnsTrueWhenMatchesUuidFormat($uuid)
    {
        $this->validator->required('guid')->uuid(Uuid::UUID_VALID);
        $result = $this->validator->validate(['guid' => $uuid]);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider correctUUIDv2
     */
    public function testReturnsTrueWhenMatchesUuidV2($uuid)
    {
        $this->validator->required('guid')->uuid(Uuid::UUID_V2);
        $result = $this->validator->validate(['guid' => $uuid]);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider correctUUIDv3
     */
    public function testReturnsTrueWhenMatchesUuidV3($uuid)
    {
        $this->validator->required('guid')->uuid(Uuid::UUID_V3);
        $result = $this->validator->validate(['guid' => $uuid]);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
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
     * @dataProvider correctUUIDv5
     */
    public function testReturnsTrueWhenMatchesUuidV5($uuid)
    {
        $this->validator->required('guid')->uuid(Uuid::UUID_V5);
        $result = $this->validator->validate(['guid' => $uuid]);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider correctUUIDNILv4v5
     */
    public function testReturnsTrueWhenMatchingMultipleUuidVersions($uuid)
    {
        $this->validator->required('guid')->uuid(Uuid::UUID_NIL | Uuid::UUID_V4 | Uuid::UUID_V5);
        $result = $this->validator->validate(['guid' => $uuid]);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider incorrectUUIDv4
     */
    public function testReturnsFalseOnNoMatch($uuid)
    {
        $this->validator->required('guid')->uuid(Uuid::UUID_V4);
        $result = $this->validator->validate(['guid' => $uuid]);
        $this->assertFalse($result->isValid());

        $expected = [
            'guid' => [
                Uuid::INVALID_UUID => 'guid must be a valid UUID (v4)'
            ]
        ];

        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * @dataProvider incorrectVersionsProvider
     */
    public function testThrowsExceptionOnUnknownVersion($version)
    {
        $this->setExpectedException(
            '\InvalidArgumentException',
            'Invalid UUID version mask given. Please choose one of the constants on the Uuid class.'
        );
        $this->validator->required('guid')->uuid(Uuid::UUID_V5 * 2);
    }

    public function testThrowsExceptionOnNegativeVersion()
    {
        $this->setExpectedException('\InvalidArgumentException', 'Invalid UUID version mask given.');
        $this->validator->required('guid')->uuid(-1);
    }
}
