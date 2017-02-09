<?php

namespace Symsonte\Http\Server\Request\Authorization\Resource;

use Symsonte\Http\Server\Request\Authorization;
use Symsonte\Http\Server\Request\MethodMatch;
use Symsonte\Http\Server\Request\UriMatch;
use Symsonte\Resource\Compiler as BaseCompiler;
use Symsonte\Resource\UnsupportedNormalizationException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.authorization.resource.compiler']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.authorization.resource.compiler']
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

        $roles = [];

        if (isset($normalization->roles['method'])) {
            $roles[] = new MethodMatch($normalization->roles['method']);
        }

        if (isset($normalization->roles['roles'])) {
            $roles[] = new UriMatch($normalization->roles['roles']);
        }

        return new Compilation(
            new Authorization(
                $normalization->key,
                $normalization->roles
            )
        );
    }
}
