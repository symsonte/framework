<?php

namespace Symsonte\Cli\Server;

use Symsonte\Call\ParametersResolver;
use Symsonte\Caller;
use Symsonte\Cli\Server;
use Symsonte\Cli\Server\Input\Resolution\Finder;
use Symsonte\Resource\Builder;
use Symsonte\Resource\DelegatorBuilder;
use Symsonte\Service\Container;
use Symsonte\ServiceKit\Resource\CachedLoader;
use ReflectionMethod;
use ReflectionException;
use LogicException;

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
class CommandDispatcher
{
    /**
     * @var Finder
     */
    private $commandFinder;

    /**
     * @var Container
     */
    private $serviceContainer;

    /**
     * @var ParametersResolver
     */
    private $parametersResolver;

    /**
     * @var Caller
     */
    private $commandCaller;

    /**
     * @var Server
     */
    private $server;

    /**
     * @param Finder    $commandFinder
     * @param Container $serviceContainer
     * @param ParametersResolver $parametersResolver
     * @param Caller    $commandCaller
     * @param Server    $server
     */
    public function __construct(
        Finder $commandFinder,
        Container $serviceContainer,
        ParametersResolver $parametersResolver,
        Caller $commandCaller,
        Server $server
    ) {
        $this->commandFinder = $commandFinder;
        $this->serviceContainer = $serviceContainer;
        $this->parametersResolver = $parametersResolver;
        $this->commandCaller = $commandCaller;
        $this->server = $server;
    }

    /**
     */
    public function dispatch()
    {
        $input = $this->server->resolveInput();

        $command = $this->commandFinder->first($input);

        list($command, $method) = explode(':', $command);

        $command = $this->serviceContainer->get($command);

        $parameters = $this->parametersResolver->resolve($command, $method);

        $response = $this->commandCaller->call($command, $method, $parameters);

        $this->server->resolveOutput()->outln(print_r($response, true));
    }
}
