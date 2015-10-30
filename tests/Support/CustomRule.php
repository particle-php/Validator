<?php
namespace Particle\Validator\Tests\Support;

use Particle\Validator\Rule;

class CustomRule extends Rule
{
    const NOT_BAR = 'CustomRule::NOT_BAR';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_BAR => '{{key}} must be equal to "bar"'
    ];

    /**
     * Validates if the value is equal to "bar".
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        if ($value !== 'bar') {
            return $this->error(self::NOT_BAR);
        }
        return true;
    }
}
