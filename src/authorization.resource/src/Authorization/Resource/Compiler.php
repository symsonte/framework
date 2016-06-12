<?php

namespace Symsonte\Authorization\Resource;

use Symsonte\Authorization;
use Symsonte\Resource\Compiler as BaseCompiler;
use Symsonte\Resource\UnsupportedNormalizationException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.authorization.resource.compiler']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.authorization.resource.compiler']
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
            new Authorization(
                $normalization->key,
                $normalization->roles
            )
        );
    }
}
