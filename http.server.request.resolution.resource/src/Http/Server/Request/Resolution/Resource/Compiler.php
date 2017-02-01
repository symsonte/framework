<?php

namespace Symsonte\Http\Server\Request\Resolution\Resource;

use Symsonte\Http\Server\Request\MethodMatch;
use Symsonte\Http\Server\Request\Resolution;
use Symsonte\Http\Server\Request\UriMatch;
use Symsonte\Resource\Compiler as BaseCompiler;
use Symsonte\Resource\UnsupportedNormalizationException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.resolution.resource.compiler']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.resolution.resource.compiler']
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

        if (isset($normalization->matches['method'])) {
            $matches[] = new MethodMatch($normalization->matches['method']);
        }

        if (isset($normalization->matches['uri'])) {
            $matches[] = new UriMatch($normalization->matches['uri']);
        }

        return new Compilation(
            new Resolution(
                $normalization->key,
                $matches
            )
        );
    }
}
