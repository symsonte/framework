<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\Builder;
use Symsonte\Resource\Compiler;
use Symsonte\Resource\UnsupportedNormalizationException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.compiler']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.compiler']
 * })
 */
class ImportsCompiler implements Compiler
{
    /**
     * @param ImportsNormalization $normalization
     *
     * @return ImportsCompilation
     *
     * @throws UnsupportedNormalizationException
     */
    public function compile($normalization)
    {
        if ($this->support($normalization) === false) {
            throw new UnsupportedNormalizationException($normalization);
        }

        return new ImportsCompilation($normalization->metadata);
    }

    /**
     * @param $declaration
     *
     * @return bool
     */
    private function support($declaration)
    {
        return $declaration instanceof ImportsNormalization;
    }
}