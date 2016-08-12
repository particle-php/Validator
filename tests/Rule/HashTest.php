<?php
namespace Particle\Validator\Tests\Rule;

use Particle\Validator\Rule\Hash;
use Particle\Validator\Validator;

class HashTest extends \PHPUnit_Framework_TestCase
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
     * @dataProvider getValidHashes
     * @param string $value
     * @param string $hashAlgorithm
     * @param bool $allowUppercase
     */
    public function testReturnsTrueOnValidHashes($value, $hashAlgorithm, $allowUppercase)
    {
        $this->validator->required('hash')->hash($hashAlgorithm, $allowUppercase);
        $result = $this->validator->validate(['hash' => $value]);
        $this->assertTrue($result->isValid());
    }

    /**
     * @dataProvider getInvalidHashes
     * @param string $value
     * @param string $hashAlgorithm
     * @param bool $allowUppercase
     */
    public function testReturnsFalseOnInvalidHashes($value, $hashAlgorithm, $allowUppercase)
    {
        $this->validator->required('hash')->hash($hashAlgorithm, $allowUppercase);
        $result = $this->validator->validate(['hash' => $value]);
        $this->assertFalse($result->isValid());
        $expected = [
            'hash' => [
                Hash::INVALID_FORMAT => sprintf('hash must be a valid %s hash', $hashAlgorithm),
            ]
        ];
        $this->assertEquals($expected, $result->getMessages());
    }

    /**
     * Returns a list of hashes considered valid.
     *
     * @return array
     */
    public function getValidHashes()
    {
        return [
            [hash('md5', ''), Hash::ALGO_MD5, Hash::DISALLOW_UPPERCASE],
            [strtoupper(hash('md5', '')), Hash::ALGO_MD5, Hash::ALLOW_UPPERCASE],
            [hash('sha1', ''), Hash::ALGO_SHA1, Hash::DISALLOW_UPPERCASE],
            [hash('sha256', ''), Hash::ALGO_SHA256, Hash::DISALLOW_UPPERCASE],
            [hash('sha512', ''), Hash::ALGO_SHA512, Hash::DISALLOW_UPPERCASE],
            [hash('crc32', ''), Hash::ALGO_CRC32, Hash::DISALLOW_UPPERCASE],
        ];
    }

    /**
     * Returns a list of hashes considered invalid.
     *
     * @return array
     */
    public function getInvalidHashes()
    {
        return [
            [hash('sha512', ''), Hash::ALGO_MD5, Hash::DISALLOW_UPPERCASE],
            [strtoupper(hash('md5', '')), Hash::ALGO_MD5, Hash::DISALLOW_UPPERCASE],
            [hash('md5', ''), Hash::ALGO_SHA1, Hash::DISALLOW_UPPERCASE],
            [hash('crc32', ''), Hash::ALGO_SHA256, Hash::DISALLOW_UPPERCASE],
            [hash('sha1', ''), Hash::ALGO_SHA512, Hash::DISALLOW_UPPERCASE],
            [hash('sha256', ''), Hash::ALGO_CRC32, Hash::DISALLOW_UPPERCASE],
        ];
    }
}
