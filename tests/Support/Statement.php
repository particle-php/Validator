<?php
namespace Particle\Validator\Tests\Support;

class Statement
{
    /**
     * @var string
     */
    protected $statement;

    /**
     * @var bool
     */
    protected $result;

    /**
     * @param string $statement
     * @param bool $result
     */
    public function __construct($statement, $result)
    {
        $this->statement = $statement;
        $this->result = $result;
    }

    /**
     * @return bool
     */
    public function __invoke()
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->statement;
    }
}
