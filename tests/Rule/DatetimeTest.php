<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Validator;
use \DateTime;

class DatetimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testRespectsFormatIfPassed()
    {
        $this->validator->required('time')->datetime('H:i');
        $result = $this->validator->isValid(['time' => '18:00']);

        $this->assertEquals([], $this->validator->getMessages());
        $this->assertTrue($result);

        $result = $this->validator->isValid(['time' => (new DateTime())->format('Y-m-d H:i:s')]);

        $this->assertFalse($result);
        $expected = [
            'time' => [
                \Particle\Validator\Rule\Datetime::INVALID_VALUE => 'time must be a valid date'
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testWillTakeManyFormatsIfNoFormatPassed()
    {
        $this->validator->required('time')->datetime();
        $this->assertTrue($this->validator->isValid(['time' => '18:00']));
        $this->assertTrue($this->validator->isValid(['time' => '2015-03-29 16:11:09']));
        $this->assertTrue($this->validator->isValid(['time' => '29-03-2015 16:11:09']));
    }

    public function testReturnsFalseOnUnparsableDate()
    {
        $this->validator->required('time')->datetime();
        $result = $this->validator->isValid(['time' => 'This is not a date. Not even close.']);

        $this->assertFalse($result);
        $expected = [
            'time' => [
                \Particle\Validator\Rule\Datetime::INVALID_VALUE => 'time must be a valid date'
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }


    /**
     * @link https://github.com/particle-php/Validator/issues/31
     */
    public function testReturnsFalseOnParsableButValidFormat()
    {
        $this->validator->required('date')->datetime('Ymd');
        $result = $this->validator->isValid([
            'date' => '12111978',
        ]);

        $this->assertFalse($result);
        $expected = [
            'date' => [
                \Particle\Validator\Rule\DateTime::INVALID_VALUE => 'date must be a valid date'
            ]
        ];

        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function getMessage($reason)
    {
        $messages = [
        ];

        return $messages[$reason];
    }
}
