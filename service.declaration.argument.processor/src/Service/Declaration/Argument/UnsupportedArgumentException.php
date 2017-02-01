<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Declaration\Argument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UnsupportedArgumentException extends \InvalidArgumentException
{
    /**
     * @var mixed
     */
    private $argument;

    /**
     * @param $argument
     */
    public function __construct($argument)
    {
        $this->argument = $argument;

        parent::__construct(print_r($argument, true));
    }

    /**
     * @return mixed
     */
    public function getArgument()
    {
        return $this->argument;
    }
}
