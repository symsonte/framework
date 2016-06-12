<?php

namespace Symsonte\Http\Resolution\Resource;

use Symsonte\Http\MethodMatch;
use Symsonte\Http\Resolution;
use Symsonte\Http\PathMatch;
use Symsonte\Resource\Compiler as BaseCompiler;
use Symsonte\Resource\UnsupportedNormalizationException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.resolution.resource.compiler']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.resolution.resource.compiler']
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

        $matches = [];

        if (isset($normalization->matches['methods'])) {
            $matches[] = new MethodMatch($normalization->matches['methods']);
        }

        if (isset($normalization->matches['path'])) {
            $matches[] = new PathMatch($normalization->matches['path']);
        }

        return new Compilation(
            new Resolution(
                $normalization->key,
                $matches
            )
        );
    }
}
