<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Url;
use Particle\Validator\Validator;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
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
        $result = $this->validator->validate(['url' => $value]);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    /**
     * @dataProvider getInvalidUrls
     * @param string $value
     * @param string $error
     */
    public function testReturnsFalseOnInvalidUrls($value, $error)
    {
        $this->validator->required('url')->url();
        $result = $this->validator->validate(['url' => $value]);
        $this->assertFalse($result->isValid());
        $expected = [
            'url' => [
                $error => 'url must be a valid URL'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    public function testFailsOnNotWhiteListedScheme()
    {
        $this->validator->required('url')->url(['http', 'https']);

        $result = $this->validator->validate([
            'url' => 'git://github.com'
        ]);

        $this->assertFalse($result->isValid());

        $expected = [
            'url' => [
                Url::INVALID_SCHEME => 'url must have one of the following schemes: http, https',
            ],
        ];

        $this->assertEquals($expected, $result->getMessages());
    }

    public function testSucceedsOnWhiteListedScheme()
    {
        $this->validator->required('url')->url(['http', 'https']);

        $result = $this->validator->validate([
            'url' => 'http://github.com',
        ]);

        $this->assertTrue($result->isValid());
    }

    public function testSucceedsOnAlternativeWhiteListedScheme()
    {
        $this->validator->required('url')->url(['mailto']);

        $result = $this->validator->validate([
            'url' => 'mailto:robbie@example.org',
        ]);

        $this->assertTrue($result->isValid());
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
