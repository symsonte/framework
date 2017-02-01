<?php

namespace Symsonte\Cli\Server\Input\Resolution\Resource;

use Symsonte\Cli\Server\Input\Resolution;
use Symsonte\Cli\Server\Input\FirstParameterMatch;
use Symsonte\Service\Declaration\Call;
use Symsonte\Resource\Compiler as BaseCompiler;
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
class Compiler implements BaseCompiler
{
    /**
     * @param Normalization $normalization
     *
     * @return Compilation
     *
     * @throws UnsupportedNormalizationException
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