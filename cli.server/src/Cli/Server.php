<?php

namespace Symsonte\Cli;

use Symsonte\Cli\Server\Input\Resolver as InputResolver;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class Server
{
    /**
     * @var InputResolver
     */
    private $inputResolver;

    /**
     * @var Input
     */
    private $input;

    /**
     * @param InputResolver $inputResolver
     *
     * @ds\arguments({
     *     inputResolver: '@symsonte.cli.server.input.aura_resolver'
     * })
     *
     * @di\arguments({
     *     inputResolver: '@symsonte.cli.server.input.aura_resolver'
     * })
     */
    function __construct(
        InputResolver $inputResolver
    )
    {
        $this->inputResolver = $inputResolver;
    }

    /**
     * @return Input
     */
    public function resolveInput()
    {
        if (!$this->input) {
            $this->input = $this->inputResolver->resolve();
        }

        return $this->input;
    }

}
