<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\Required;
use Particle\Validator\Validator;

class RequiredTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    public function setUp()
    {
        $this->validator = new Validator();
    }

    public function testReturnsFalseOnUnsetRequiredValue()
    {
        $this->validator->required('foo');

        $result = $this->validator->isValid([
        ]);

        $this->assertFalse($result);
    }

    public function testReturnsTrueOnSetRequiredValues()
    {
        $this->validator->required('foo');
        $result = $this->validator->isValid([
            'foo' => 'bar'
        ]);
        $this->assertTrue($result);
    }

    public function testReturnsTrueOnAnyValue()
    {
        $values = [false, null, true, 0, '', 'string', 0.00];

        $this->validator->required('foo');

        foreach ($values as $value) {
            $this->validator->isValid(['foo' => $value]);

            $this->assertArrayNotHasKey(
                Required::NON_EXISTENT_KEY,
                $this->validator->getMessages()
            );
        }
    }

    public function testRequiredCanBeConditional()
    {
        $this->validator->optional('first_name')->required(function (array $values) {
            return $values['foo'] === 'bar';
        });

        $result = $this->validator->isValid(['foo' => 'bar']);

        $this->assertFalse($result);
        $this->assertEquals(
            [
                'first_name' => [
                    Required::NON_EXISTENT_KEY => 'first_name must be provided, but does not exist',
                ]
            ],
            $this->validator->getMessages()
        );

        $result = $this->validator->isValid(['foo' => 'not bar!']);
        $this->assertTrue($result);
        $this->assertEquals([], $this->validator->getMessages());
    }
}
