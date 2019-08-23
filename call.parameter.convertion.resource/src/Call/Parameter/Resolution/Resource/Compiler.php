<?php

namespace Symsonte\Call\Parameter\Resolution\Resource;

use Symsonte\Call\Parameter\Resolution;
use Symsonte\Resource\Compiler as BaseCompiler;
use Symsonte\Resource\UnsupportedNormalizationException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.call.parameter.resolution.resource.compiler']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.call.parameter.resolution.resource.compiler']
 * })
 */
class Compiler implements BaseCompiler
{
    /**
     * @param Normalization $normalization
     *
     * @throws UnsupportedNormalizationException
     *
     * @return Compilation
     */
    public function compile($normalization)
    {
        if (!$normalization instanceof Normalization) {
            throw new UnsupportedNormalizationException($normalization);
        }

        return new Compilation(
            new Resolution(
                $normalization->class,
                $normalization->method,
                $normalization->parameter,
                $normalization->value
            )
        );
    }
}
