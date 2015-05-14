<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\Url;
use Particle\Validator\Validator;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    /**
     * @dataProvider getValidUrls
     * @param string $value
     */
    public function testReturnsTrueOnValidUrls($value)
    {
        $this->validator->required('url')->url();
        $this->assertTrue($this->validator->validate(['url' => $value]));
        $this->assertEquals([], $this->validator->getMessages());
    }

    /**
     * @dataProvider getInvalidUrls
     * @param string $value
     * @param string $error
     */
    public function testReturnsFalseOnInvalidUrls($value, $error)
    {
        $this->validator->required('url')->url();
        $this->assertFalse($this->validator->validate(['url' => $value]));
        $expected = [
            'url' => [
                Url::INVALID_URL => 'The value of "url" must be a valid URL'
            ]
        ];
        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function getValidUrls()
    {
        return [
            ['http://github.com'],
            ['git://berry:berry@github.com/berry-langerak/Validator?view=source&value=yes']
        ];
    }

    public function getInvalidUrls()
    {
        return [
            ['malformed:/github.com', Url::INVALID_URL],
            ['http:///github.com', Url::INVALID_URL]
        ];
    }
}
