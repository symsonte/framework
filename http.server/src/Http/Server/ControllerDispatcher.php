<?php

namespace Symsonte\Http\Server;

use Symsonte\ConstructorInstantiator as BaseConstructorInstantiator;
use Symsonte\Http\Server;
use Symsonte\Http\Server\Request\Authentication\Credential\AuthorizationResolver;
use Symsonte\Http\Server\Request\Authentication\Credential\InvalidDataException;
use Symsonte\Http\Server\Request\Authentication\Credential\Processor as CredentialProcessor;
use Symsonte\Http\Server\Request\Authorization\Checker;
use Symsonte\Http\Server\Request\Authorization\Role\Collector as RoleCollector;
use Symsonte\Http\Server\Request\Resolution\Finder;
use Symsonte\Resource\Builder;
use Symsonte\Resource\DelegatorBuilder;
use Symsonte\Service\CachedInstantiator;
use Symsonte\Service\ConstructorInstantiator;
use Symsonte\Service\Container;
use Symsonte\Service\Declaration\Argument\ServiceProcessor as ServiceArgumentProcessor;
use Symsonte\Service\Declaration\Call\Processor as CallProcessor;
use Symsonte\Service\Declaration\IdStorer;
use Symsonte\Service\Declaration\Storer;
use Symsonte\Service\DeductibleContainer;
use Symsonte\Service\OrdinaryContainer;
use Symsonte\ServiceKit\Declaration\Bag;
use Symsonte\ServiceKit\Resource\Loader;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ControllerDispatcher
{
    /**
     * @var Loader
     */
    private $resourceLoader;

    /**
     * @var Builder
     */
    private $resourceBuilder;

    /**
     * @var Container
     */
    private $serviceContainer;

    /**
     * @var Finder
     */
    private $controllerFinder;

    /**
     * @var Checker
     */
    private $authorizationChecker;

    /**
     * @var AuthorizationResolver
     */
    private $authorizationResolver;

    /**
     * @var CredentialProcessor|null
     */
    private $credentialProcessor;

    /**
     * @var RoleCollector|null
     */
    private $roleCollector;
    /**
     * @var Server
     */
    private $server;

    /**
     * @param Loader                   $resourceLoader
     * @param Builder[]                $resourceBuilders
     * @param Container                $serviceContainer
     * @param Finder                   $controllerFinder
     * @param Checker                  $authorizationChecker
     * @param AuthorizationResolver    $authorizationResolver
     * @param CredentialProcessor|null $credentialProcessor
     * @param RoleCollector|null       $roleCollector
     * @param Server                   $server
     */
    public function __construct(
        Loader $resourceLoader,
        array $resourceBuilders,
        Container $serviceContainer,
        Finder $controllerFinder,
        Checker $authorizationChecker,
        AuthorizationResolver $authorizationResolver,
        CredentialProcessor $credentialProcessor = null,
        RoleCollector $roleCollector = null,
        Server $server
    ) {
        $this->resourceLoader = $resourceLoader;
        $this->resourceBuilder = new DelegatorBuilder($resourceBuilders);
        $this->serviceContainer = $serviceContainer;
        $this->controllerFinder = $controllerFinder;
        $this->authorizationChecker = $authorizationChecker;
        $this->authorizationResolver = $authorizationResolver;
        $this->credentialProcessor = $credentialProcessor;
        $this->roleCollector = $roleCollector;
        $this->server = $server;
    }

    public function dispatch()
    {
        $method = $this->server->resolveMethod();
        $uri = $this->server->resolveUri();
        $version = $this->server->resolverVersion();
        $headers = $this->server->resolveHeaders();
        $body = $this->server->resolveBody();

        if ($method == 'OPTIONS') {
            $this->server->sendResponse();

            return;
        }

        $info = $this->controllerFinder->first($method, $uri, $version, $headers, $body);
        $controller = $info[1];
        $variables = [];
        if (isset($info[2])) {
            $variables = $info[2];
        }

        // Does the controller require authorization?
        if ($this->authorizationChecker->has($controller) === true) {
            // Process the credential
            try {
                $uniqueness = $this->credentialProcessor->process();
            } catch (InvalidDataException $e) {
                $this->server->sendResponse(
                    null,
                    401
                );

                return;
            }

            // Get the user roles
            $roles = $this->roleCollector->collect($uniqueness);
            // Doesn't the user have the correct role for the current controller?
            if ($this->authorizationChecker->check($controller, $roles) === false) {
                $this->server->sendResponse(
                    null,
                    401
                );

                return;
            }
        }

        list($controller, $method) = explode(':', $controller);
        $controller = $this->createContainer()->get($controller);
        $reflectionMethod = new \ReflectionMethod($controller, $method);
        $parameters = $reflectionMethod->getParameters();
        $artificialParameters = [];
        foreach ($parameters as $parameter) {
            if ($parameter->getName() == 'uniqueness' && isset($uniqueness)) {
                $artificialParameters['uniqueness'] = $uniqueness;
            } elseif (array_key_exists($parameter->getName(), $variables)) {
                $artificialParameters[$parameter->getName()] = $variables[$parameter->getName()];
            }
        }

        call_user_func_array([$controller, $method], $artificialParameters);
    }

    /**
     * @return DeductibleContainer
     */
    private function createContainer()
    {
        $bag = $this->resourceLoader->load($this->resourceBuilder->build([
            'dir'    => sprintf('%s/../../../../../../../http', __DIR__),
            'filter' => '*.php',
            'extra'  => [
                'type'       => 'annotation',
                'annotation' => '/^di\\\\controller/',
            ],
        ]));

        $declarationStorer = $this->createDeclarationStorer($bag);
        $argumentProcessor = new ServiceArgumentProcessor();
        $argumentProcessor->setContainer($this->serviceContainer);

        return new DeductibleContainer(
            $this->createDeductibleStorer($bag),
            $declarationStorer,
            new OrdinaryContainer(
                $declarationStorer,
                new CachedInstantiator(
                    new ConstructorInstantiator(
                        $argumentProcessor,
                        new CallProcessor($argumentProcessor),
                        new BaseConstructorInstantiator()
                    )
                )
            )
        );
    }

    /**
     * @param Bag $bag
     *
     * @return IdStorer
     */
    private function createDeductibleStorer(Bag $bag)
    {
        $storer = new IdStorer();

        foreach ($bag->getDeclarations() as $declaration) {
            if ($declaration->isDeductible()) {
                $storer->add($declaration->getDeclaration()->getId());
            }
        }

        return $storer;
    }

    /**
     * @param Bag $bag
     *
     * @return Storer
     */
    private function createDeclarationStorer(Bag $bag)
    {
        $storer = new Storer();

        foreach ($bag->getDeclarations() as $declaration) {
            $storer->add($declaration->getDeclaration());
        }

        return $storer;
    }
}
