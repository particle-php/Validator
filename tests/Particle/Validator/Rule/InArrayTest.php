<?php
use Particle\Validator\Rule\InArray;
use Particle\Validator\Validator;

class InArrayTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsTrueIfValueIsInArrayWithStrictChecking()
    {
        $this->validator->required('group')->inArray(['foo', 'bar']);
        $this->assertTrue($this->validator->validate(['group' => 'foo']));
    }

    public function testReturnsFalseIfValueIsNotInArrayWithStrictChecking()
    {
        $this->validator->required('group')->inArray([0]);
        $this->assertFalse($this->validator->validate(['group' => '0']));

        $expected = [
            'group' => [
                InArray::NOT_IN_ARRAY => 'group must be in the defined set of values'
            ]
        ];

        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testCanUseTheValuesInErrorMessage()
    {
        $this->validator->required('group')->inArray(['users', 'admins']);

        $this->validator->overwriteMessages([
            'group' => [
                InArray::NOT_IN_ARRAY => '{{ name }} must be one of {{ values }}'
            ]
        ]);
        $this->assertFalse($this->validator->validate(['group' => 'none']));

        $expected = [
            'group' => [
                InArray::NOT_IN_ARRAY => 'group must be one of "users", "admins"'
            ]
        ];

        $this->assertEquals($expected, $this->validator->getMessages());
    }

    public function testReturnsTrueIfValueIsSortOfInArrayWithoutStrictChecking()
    {
        $this->validator->required('group')->inArray([0], false);
        $this->assertTrue($this->validator->validate(['group' => '0']));
    }
}
