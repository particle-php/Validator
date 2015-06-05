<?php
namespace Particle\Tests\Rule;

use Particle\Validator\Rule\Bool;
use Particle\Validator\Validator;

class BoolTest extends \PHPUnit_Framework_TestCase
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
        $result = $this->validator->isValid(['active' => $value]);
        $this->assertEquals($expected, $result);

        if ($expected === false) {
            $this->assertEquals(
                $this->getMessage(Bool::NOT_BOOL),
                $this->validator->getMessages()['active'][Bool::NOT_BOOL]
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
            Bool::NOT_BOOL => 'active must be either true or false'
        ];

        return $messages[$reason];
    }
}
