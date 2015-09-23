<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Boolean;
use Particle\Validator\Validator;

class BooleanTest extends \PHPUnit_Framework_TestCase
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
     * @param mixed $value
     * @param bool $expected
     * @dataProvider getTestValuesAndResults
     */
    public function testReturnsTrueOnlyOnValidBools($value, $expected)
    {
        $this->validator->required('active')->bool();
        $result = $this->validator->validate(['active' => $value]);
        $this->assertEquals($expected, $result->isValid());

        if ($expected === false) {
            $this->assertEquals(
                $this->getMessage(Boolean::NOT_BOOL),
                $result->getMessages()['active'][Boolean::NOT_BOOL]
            );
        }
    }

    /**
     * @return array
     */
    public function getTestValuesAndResults()
    {
        return [
            [true, true],
            [false, true],
            ["true", false],
            ["yes", false],
            [1, false],
            [0, false]
        ];
    }

    public function getMessage($reason)
    {
        $messages = [
            Boolean::NOT_BOOL => 'active must be either true or false'
        ];

        return $messages[$reason];
    }
}
