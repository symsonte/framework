<?php

namespace Symsonte\Cli\Server\Input\Resolution\Resource;

use Symsonte\Cli\Server\Input\FirstParameterMatch;
use Symsonte\Cli\Server\Input\Resolution;
use Symsonte\Resource\Compiler as BaseComposer;
use Symsonte\Resource\UnsupportedNormalizationException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.cli.server.input.resolution.resource.compiler']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.cli.server.input.resolution.resource.compiler']
 * })
 */
class Compiler implements BaseComposer
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

        if (isset($normalization->matches['command'])) {
            $matches[] = new FirstParameterMatch($normalization->matches['command']);
        }

        return new Compilation(
            new Resolution(
                $normalization->key,
                $matches
            )
        );
    }
}
