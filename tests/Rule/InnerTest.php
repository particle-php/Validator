<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Inner;
use Particle\Validator\Validator;

class InnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Validator
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    public function testCanValidateNestedArrays()
    {
        $data = [
            'user' => [
                'id' => 1,
                'first_name' => 'Alexander',
                'last_name' => 'Kochetov',
                'profile' => [
                    'email' => 'creocoder@gmail.com',
                    'registered_at' => '2016-09-24',
                ],
            ],
        ];

        $this->validator->required('user')->inner(function (Validator $userValidator) {
            $userValidator->required('id')->integer();
            $userValidator->required('first_name')->string();
            $userValidator->required('last_name')->string();
            $userValidator->required('profile')->inner(function (Validator $profileValidator) {
                $profileValidator->required('email')->email();
                $profileValidator->required('registered_at')->datetime('Y-m-d');
            });
        });

        $result = $this->validator->validate($data);

        $this->assertTrue($result->isValid());
        $this->assertEquals([], $result->getMessages());
    }

    public function testReturnsErrorOnNonArray()
    {
        $this->validator->required('foo')->inner(function (Validator $validator) {
            $validator->required('bar')->bool();
        });

        $result = $this->validator->validate([
            'foo' => 1
        ]);

        $this->assertFalse($result->isValid());

        $expected = [
            'foo' => [
                Inner::NOT_AN_ARRAY => 'foo must be an array',
            ]
        ];

        $this->assertEquals($expected, $result->getMessages());
    }
}
