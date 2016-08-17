<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\InArray;
use Particle\Validator\Validator;

class InArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueIfValueIsInArrayWithStrictChecking()
    {
        $this->validator->required('group')->inArray(['foo', 'bar']);
        $result = $this->validator->validate(['group' => 'foo']);
        $this->assertTrue($result->isValid());
    }

    public function testReturnsFalseIfValueIsNotInArrayWithStrictChecking()
    {
        $this->validator->required('group')->inArray([0]);
        $result = $this->validator->validate(['group' => '0']);
        $this->assertFalse($result->isValid());

        $expected = [
            'group' => [
                InArray::NOT_IN_ARRAY => 'group must be in the defined set of values'
            ]
        ];

        $this->assertEquals($expected, $result->getMessages());
    }

    public function testCanUseTheValuesInErrorMessage()
    {
        $this->validator->required('group')->inArray(['users', 'admins']);

        $this->validator->overwriteMessages([
            'group' => [
                InArray::NOT_IN_ARRAY => '{{ name }} must be one of {{ values }}'
            ]
        ]);
        $result = $this->validator->validate(['group' => 'none']);
        $this->assertFalse($result->isValid());

        $expected = [
            'group' => [
                InArray::NOT_IN_ARRAY => 'group must be one of "users", "admins"'
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    public function testReturnsTrueIfValueIsSortOfInArrayWithoutStrictChecking()
    {
        $this->validator->required('group')->inArray([0], InArray::NOT_STRICT);
        $result = $this->validator->validate(['group' => '0']);
        $this->assertTrue($result->isValid());
    }
}
