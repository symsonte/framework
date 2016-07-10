<?php

namespace Symsonte\ServiceKit\Resource\Argument;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DelegatorCompiler implements Compiler
{
    /**
     * @var Compiler[]
     */
    protected $compilers;

    /**
     * @param Compiler[] $compilers
     */
    public function __construct(array $compilers = [])
    {
        $this->compilers = $compilers;
    }

    /**
     * {@inheritdoc}
     */
    public function compile($argument)
    {
        foreach ($this->compilers as $compiler) {
            try {
                return $compiler->compile($argument);
            } catch (UnsupportedArgumentException $e) {
                continue;
            }
        }

        throw new UnsupportedArgumentException($argument);
    }
}
