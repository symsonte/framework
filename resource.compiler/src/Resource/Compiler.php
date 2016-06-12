<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Compiler
{
    /**
     * Compiles given normalization.
     *
     * @param mixed $normalization
     *
     * @return mixed
     */
    public function compile($normalization);
}
