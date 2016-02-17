<?php
/**
 * Particle.
 *
 * @link      http://github.com/particle-php for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Particle (http://particle-php.com)
 * @license   https://github.com/particle-php/validator/blob/master/LICENSE New BSD License
 */
namespace Particle\Validator\Rule;

use Particle\Validator\Rule;

/**
 * This rule is for validating if a value is a valid cryptographic hash.
 *
 * @package Particle\Validator\Rule
 */
class Hash extends Rule
{
    const ALGO_MD5 = 'md5';
    const ALGO_SHA1 = 'sha1';
    const ALGO_SHA256 = 'sha256';
    const ALGO_SHA512 = 'sha512';
    const ALGO_CRC32 = 'crc32';

    /**
     * A constant that will be used when the value is not a valid cryptographic hash.
     */
    const INVALID_FORMAT = 'Hash::INVALID_FORMAT';

    /**
     * The message templates which can be returned by this validator.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID_FORMAT => '{{ name }} must be a valid hash'
    ];

    /**
     * @var string
     */
    protected $hashAlgorithm;

    /**
     * @var bool
     */
    protected $allowUppercase;

    /**
     * Construct the Hash validator.
     *
     * @param string $hashAlgorithm
     * @param bool $allowUppercase
     */
    public function __construct($hashAlgorithm, $allowUppercase = false)
    {
        $this->hashAlgorithm = $hashAlgorithm;
        $this->allowUppercase = $allowUppercase;

        $this->messageTemplates = [
            self::INVALID_FORMAT => sprintf('{{ name }} must be a valid %s hash', $hashAlgorithm)
        ];
    }

    /**
     * Validates if the value is a valid cryptographic hash.
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value)
    {
        $caseSensitive = $this->allowUppercase ? 'i' : '';

        switch ($this->hashAlgorithm) {
            case self::ALGO_MD5:
                if (preg_match('/^[0-9a-f]{32}$/' . $caseSensitive, $value) === 1) {
                    return true;
                }
                break;
            case self::ALGO_SHA1:
                if (preg_match('/^[0-9a-f]{40}$/' . $caseSensitive, $value) === 1) {
                    return true;
                }
                break;
            case self::ALGO_SHA256:
                if (preg_match('/^[0-9a-f]{64}$/' . $caseSensitive, $value) === 1) {
                    return true;
                }
                break;
            case self::ALGO_SHA512:
                if (preg_match('/^[0-9a-f]{128}$/' . $caseSensitive, $value) === 1) {
                    return true;
                }
                break;
            case self::ALGO_CRC32:
                if (preg_match('/^[0-9a-f]{8}$/' . $caseSensitive, $value) === 1) {
                    return true;
                }
                break;
        }

        return $this->error(self::INVALID_FORMAT);
    }
}
