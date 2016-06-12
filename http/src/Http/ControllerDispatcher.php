<?php

namespace Symsonte\Http;

use Symsonte\Caller;
use Symsonte\Call\ParametersResolver;
use Symsonte\Http\Resolution\Finder;
use Symsonte\Service\Container;
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
class ControllerDispatcher
{
    /**
     * @var Finder
     */
    private $controllerFinder;

    /**
     * @var PreDispatcher[]
     */
    private $preDispatcherServices;

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
    private $controllerCaller;

    /**
     * @var Server
     */
    private $server;

    /**
     * @ds\arguments({
     *     preDispatcherServices: '@symsonte.http.pre_dispatcher'
     * })
     *
     * @di\arguments({
     *     preDispatcherServices: '#symsonte.http.pre_dispatcher'
     * })
     *
     * @param Finder             $controllerFinder
     * @param PreDispatcher[]    $preDispatcherServices
     * @param Container          $serviceContainer
     * @param ParametersResolver $parametersResolver
     * @param Caller       $controllerCaller
     * @param Server             $server
     */
    public function __construct(
        Finder $controllerFinder,
        array $preDispatcherServices,
        Container $serviceContainer,
        ParametersResolver $parametersResolver,
        Caller $controllerCaller,
        Server $server
    ) {
        $this->controllerFinder = $controllerFinder;
        $this->preDispatcherServices = $preDispatcherServices;
        $this->serviceContainer = $serviceContainer;
        $this->parametersResolver = $parametersResolver;
        $this->controllerCaller = $controllerCaller;
        $this->server = $server;
    }

    /**
     */
    public function dispatch()
    {
        $method = $this->server->resolveMethod();
        $path = $this->server->resolvePath();

        if ($method == 'OPTIONS') {
            $this->server->sendResponse();

            return null;
        }

        try {
            list($controller) = $this->controllerFinder->first($method, $path);
        } catch (Resolution\NotFoundException $e) {
            throw new LogicException(null, null, $e);
        }

        /* PreDispatcher */

        foreach ($this->preDispatcherServices as $dispatcherService) {
            $response = $dispatcherService->dispatch($controller);

            if ($response) {
                $this->server->sendResponse(
                    $response->getContent(),
                    $response->getStatus(),
                    $response->getHeaders()
                );

                return;
            }
        }

        /* Dispatch */

        list($controller, $method) = explode(':', $controller);

        $controller = $this->serviceContainer->get($controller);

        $parameters = $this->parametersResolver->resolve($controller, $method);

        $result = $this->controllerCaller->call($controller, $method, $parameters);

        if ($result instanceof OrdinaryResponse) {
            $body = $result->getContent();
            $status = $result->getStatus();
            $headers = $result->getHeaders();
        } else {
            $body = $result;
            $status = '200';
            $headers = [];
        }

        $this->server->sendResponse($body, $status, $headers);
    }

//    /**
//     * @param string $dir
//     *
//     * @return DeductibleContainer
//     */
//    private function createContainer($dir)
//    {
//        $bag = $this->resourceLoader->load($this->resourceBuilder->build([
//            'dir'    => $dir,
//            'filter' => '*.php',
//            'extra'  => [
//                'type'       => 'annotation',
//                'annotation' => '/^di\\\\/',
//            ],
//        ], new Bag()));
//
//        $declarationStorer = $this->createDeclarationStorer($bag);
//        $argumentProcessor = new ServiceArgumentProcessor();
//        $argumentProcessor->setContainer($this->serviceContainer);
//
//        return new DeductibleContainer(
//            $this->createDeductibleStorer($bag),
//            $declarationStorer,
//            new Container(
//                $declarationStorer,
//                new CachedInstantiator(
//                    new ConstructorInstantiator(
//                        $argumentProcessor,
//                        new CallProcessor($argumentProcessor),
//                        new BaseConstructorInstantiator()
//                    )
//                )
//            )
//        );
//    }
//
//    /**
//     * @param Bag $bag
//     *
//     * @return IdStorer
//     */
//    private function createDeductibleStorer(Bag $bag)
//    {
//        $storer = new IdStorer();
//
//        foreach ($bag->getDeclarations() as $declaration) {
//            if ($declaration->isDeductible()) {
//                $storer->add($declaration->getDeclaration()->getId());
//            }
//        }
//
//        return $storer;
//    }
//
//    /**
//     * @param Bag $bag
//     *
//     * @return Storer
//     */
//    private function createDeclarationStorer(Bag $bag)
//    {
//        $storer = new Storer();
//
//        foreach ($bag->getDeclarations() as $declaration) {
//            $storer->add($declaration->getDeclaration());
//        }
//
//        return $storer;
//    }
}
