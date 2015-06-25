<?php

namespace Particle\Validator\Exception;

class ValidationNotRunException extends \LogicException
{
    protected $message = 'Validation has not run yet';
}
