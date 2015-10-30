<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/validator/blob/master/LICENSE New BSD License
 */
namespace Particle\Validator\Exception;

use Particle\Validator\ExceptionInterface;

/**
 * The ValidationNotRunException is thrown when a method is called that requires validation to be run
 *
 * @package Particle\Validator
 */
class ValidationNotRunException extends \LogicException implements ExceptionInterface
{
    /**
     * @var string
     */
    protected $message = 'Validation has not run yet';
}
