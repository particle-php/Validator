<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Required;
use Particle\Validator\Validator;

class RequiredTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsFalseOnUnsetRequiredValue()
    {
        $this->validator->required('foo');

        $result = $this->validator->validate([
        ]);

        $this->assertFalse($result->isValid());
    }

    public function testReturnsTrueOnSetRequiredValues()
    {
        $this->validator->required('foo');
        $result = $this->validator->validate([
            'foo' => 'bar'
        ]);
        $this->assertTrue($result->isValid());
    }

    public function testReturnsTrueOnAnyValue()
    {
        $values = [false, null, true, 0, '', 'string', 0.00];

        $this->validator->required('foo');

        foreach ($values as $value) {
            $result = $this->validator->validate(['foo' => $value]);

            $this->assertArrayNotHasKey(
                Required::NON_EXISTENT_KEY,
                $result->getMessages()
            );
        }
    }

    public function testRequiredCanBeConditional()
    {
        $this->validator->optional('first_name')->required(function (array $values) {
            return $values['foo'] === 'bar';
        });

        $result = $this->validator->validate(['foo' => 'bar']);

        $this->assertFalse($result->isValid());
        $this->assertEquals(
            [
                'first_name' => [
                    Required::NON_EXISTENT_KEY => 'first_name must be provided, but does not exist',
                ]
            ],
            $result->getMessages()
        );

        $result = $this->validator->validate(['foo' => 'not bar!']);
        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }
}
