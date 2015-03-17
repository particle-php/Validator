<?php
namespace Particle\Validator\Rule;

use Particle\Validator\Rule;

class Length extends Rule
{
    const TOO_SHORT = 'Length::TOO_SHORT';
    const TOO_LONG = 'Length::TOO_LONG';

    protected $messageTemplates = [
        self::TOO_SHORT => 'The value of "{{ name }}" is too short, should be {{ length }} characters long',
        self::TOO_LONG => 'The value of "{{ name }}" is too long, should be {{ length }} characters long'
    ];

    public function __construct($length)
    {
        $this->length = $length;
    }

    public function validate($value)
    {
        $actualLength = strlen($value);

        if ($actualLength > $this->length) {
            return $this->error(self::TOO_LONG);
        } elseif ($actualLength < $this->length) {
            return $this->error(self::TOO_SHORT);
        }
        return true;
    }

    protected function getMessageParameters()
    {
        return array_merge(parent::getMessageParameters(), [
            'length' => $this->length
        ]);
    }
}
