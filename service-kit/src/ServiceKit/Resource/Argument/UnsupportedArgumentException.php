<?php

namespace Symsonte\ServiceKit\Resource\Argument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UnsupportedArgumentException extends \Exception
{
    /**
     * @var mixed
     */
    private $argument;

    /**
     * @param mixed $argument
     */
    public function __construct($argument)
    {
        $this->argument = $argument;

        parent::__construct(sprintf("Parameter %s is unsupported.", var_export($argument, true)));
    }

    /**
     * @return mixed
     */
    public function getArgument()
    {
        return $this->argument;
    }
}