<?php
namespace Particle\Tests;

use Particle\Validator\Rule;
use Particle\Validator\Validator;

class ValidatorOutputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * Set the validator for local usage
     */
    public function setUp()
    {
        $this->validator = new Validator();
    }

    /**
     * Check if the returned rules array is what is expected
     */
    public function testOutputRulesArray()
    {
        $this->validator->required('first_name')->length(2);

        $output = $this->validator->output();

        $expected = [
            'first_name' => [
                [
                    'name' => 'Required',
                    'options' => [],
                ],
                [
                    'name' => 'NotEmpty',
                    'options' => [],
                ],
                [
                    'name' => 'Length',
                    'options' => [
                        'length' => 2,
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $output);
    }

    /**
     * Check if the returned rules array is what is expected
     */
//    public function testOutputRulesArrayCustomMessages()
//    {
//        $this->validator->required('first_name')->length(2);
//
//        $this->validator->overWriteMessages([
//            'first_name' => [
//                Rule\Length::TOO_SHORT => 'Your first name is too short bro!',
//                Rule\Length::TOO_LONG => 'Your first name is too long bro!',
//            ],
//        ]);
//
//        $output = $this->validator->output();
//
//        $expected = [
//            Rule\Length::TOO_SHORT => 'Your first name is too short bro',
//            Rule\Length::TOO_LONG => 'Your first name is too long bro!',
//        ];
//
//        $this->assertEquals($expected, $output['first_name'][2]['messages']);
//    }

    /**
     * Check that an empty array is returned when no rules have been defined
     */
    public function testOutputEmptyRulesArray()
    {
        $output = $this->validator->output();

        $this->assertEquals([], $output);
    }

    /**
     * Test that the output could be converted to json
     */
    public function testOutputJson()
    {
        $output = $this->validator->output(function($ruleSet) {
            return json_encode($ruleSet);
        });

        $this->assertSame('[]', $output);
    }

    /**
     * Test that the output could be converted to json
     */
    public function testOutputJsonWithRules()
    {
        $this->validator->required('first_name')->length(2);

        $output = $this->validator->output(function($ruleSet) {
            return json_encode($ruleSet);
        });

        $this->assertSame('[]', $output);
    }
}
