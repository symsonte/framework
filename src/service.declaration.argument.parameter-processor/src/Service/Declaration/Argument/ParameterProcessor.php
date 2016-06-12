<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Declaration\ParameterArgument;
use Symsonte\Service\Declaration\ParameterStorer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ParameterProcessor implements Processor
{
    /**
     * @var ParameterStorer
     */
    private $storer;

    /**
     * @param ParameterStorer $storer
     */
    public function __construct(ParameterStorer $storer)
    {
        $this->storer = $storer;
    }

    /**
     * @param ParameterArgument $argument
     *
     * @throws UnsupportedArgumentException if the argument is not supported.
     *
     * @return mixed
     */
    public function process($argument)
    {
        if (!$this->support($argument)) {
            throw new UnsupportedArgumentException($argument);
        }

        return $this->storer->get($argument->getKey());
    }

    /**
     * @param $argument
     *
     * @return bool
     */
    private function support($argument)
    {
        return $argument instanceof ParameterArgument;
    }
}
