<?php

namespace Symsonte\Http\Request;

use Symsonte\Call\Parameter\ResolutionStorer;
use Symsonte\Call\ParameterResolver;
use Symsonte\Http;

/**
 * @di\service({
 *     tags: ['symsonte.call.parameter_resolver']
 * })
 */
class BodyParameterResolver implements ParameterResolver
{
    /**
     * @var ResolutionStorer
     */
    private $resolutionStorer;

    /**
     * @var Http\Server
     */
    private $server;

    /**
     * @param ResolutionStorer $resolutionStorer
     * @param Http\Server      $server
     */
    public function __construct(
        ResolutionStorer $resolutionStorer,
        Http\Server $server
    ) {
        $this->resolutionStorer = $resolutionStorer;
        $this->server = $server;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(
        string $class,
        string $method,
        array $parameters
    ) {
        $convertions = [];
        
        $key = $this->resolutionStorer->find(
            $class,
            $method,
            'http\request\body'
        );

        if (!$key) {
            return $convertions;
        }

        $convertions[$key] = $this->server->resolveBody();

        $key = $this->resolutionStorer->find(
            $class,
            $method,
            'http\request\parsedBody'
        );

        if (!$key) {
            return $convertions;
        }

        $convertions[$key] = $this->server->resolveParsedBody();

        return $convertions;
    }
}
