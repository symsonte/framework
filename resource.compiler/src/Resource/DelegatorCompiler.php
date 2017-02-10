<?php

namespace Symsonte\Resource;

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
    function __construct($compilers = [])
    {
        $this->compilers = $compilers;
    }

    /**
     * {@inheritdoc}
     */
    public function compile($normalization)
    {
        foreach ($this->compilers as $compiler) {
            try {
                return $compiler->compile($normalization);
            } catch (UnsupportedNormalizationException $e) {
                continue;
            }
        }

        throw new UnsupportedDeclarationException($normalization);
    }
}