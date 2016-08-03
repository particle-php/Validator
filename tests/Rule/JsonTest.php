<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Json;
use Particle\Validator\Validator;

class JsonTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getValidJsonStrings
     * @param string $value
     */
    public function testReturnsTrueOnValidJsonString($value)
    {
        $this->validator->required('json')->json();
        $result = $this->validator->validate(['json' => $value]);
        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getInvalidJsonStrings
     * @param string $value
     */
    public function testReturnsFalseOnInvalidJsonString($value)
    {
        $this->validator->required('json')->json();
        $result = $this->validator->validate(['json' => $value]);
        $this->assertFalse($result->isValid());
        $expected = [
            'json' => [
                Json::INVALID_FORMAT => 'json must be a valid JSON string'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * Returns a list of JSON strings considered valid.
     *
     * @return array
     */
    public function getValidJsonStrings()
    {
        return [
            ['{}'],
            ['[]'],
            ['{"a": "b", "c": "d"}'],
            ['{"a": null, "c": true}'],
            ['{"a": 9, "c": 9.99}'],
            ['9'],
            ['"json"'],
        ];
    }

    /**
     * Returns a list of JSON strings considered invalid.
     *
     * @return array
     */
    public function getInvalidJsonStrings()
    {
        return [
            ['["a": "b"'],
            ["{'a': 'b'}"],
            ['json'],
            [9],
        ];
    }
}
