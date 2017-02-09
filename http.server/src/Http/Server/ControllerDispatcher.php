<?php

namespace Symsonte\Http\Server;

use Symsonte\ConstructorInstantiator as BaseConstructorInstantiator;
use Symsonte\Http\Server;
use Symsonte\Http\Server\Request\Authentication\Credential\InvalidDataException;
use Symsonte\Http\Server\Request\Authentication\Credential\Processor as CredentialProcessor;
use Symsonte\Http\Server\Request\Authentication\Credential\Resolver as CredentialResolver;
use Symsonte\Http\Server\Request\Authentication\Credential\UnresolvableException;
use Symsonte\Http\Server\Request\Authorization\Checker;
use Symsonte\Http\Server\Request\Authorization\Role\Collector as RoleCollector;
use Symsonte\Http\Server\Request\Resolution\Finder;
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
     * @var CredentialResolver
     */
    private $credentialResolver;

    /**
     * @var CredentialProcessor
     */
    private $credentialProcessor;

    /**
     * @var RoleCollector
     */
    private $roleCollector;
    /**
     * @var Server
     */
    private $server;

    /**
     * @param Loader              $resourceLoader
     * @param Container           $serviceContainer
     * @param Finder              $controllerFinder
     * @param Checker             $authorizationChecker
     * @param CredentialResolver  $credentialResolver
     * @param CredentialProcessor $credentialProcessor
     * @param RoleCollector       $roleCollector
     * @param Server              $server
     */
    public function __construct(
        Loader $resourceLoader,
        Container $serviceContainer,
        Finder $controllerFinder,
        Checker $authorizationChecker,
        CredentialResolver $credentialResolver,
        CredentialProcessor $credentialProcessor,
        RoleCollector $roleCollector,
        Server $server
    ) {
        $this->resourceLoader = $resourceLoader;
        $this->serviceContainer = $serviceContainer;
        $this->controllerFinder = $controllerFinder;
        $this->authorizationChecker = $authorizationChecker;
        $this->credentialResolver = $credentialResolver;
        $this->credentialProcessor = $credentialProcessor;
        $this->roleCollector = $roleCollector;
        $this->server = $server;
    }

    public function dispatch()
    {
        $request = $this->server->resolveRequest();

        if ($request instanceof OptionsRequest) {
            $this->server->sendResponse(new OrdinaryResponse());

            return;
        }

        $info = $this->controllerFinder->first($request);
        $controller = $info[1];
        if (isset($info[2])) {
            $variables = $info[2];
        }

        // Does the controller require authorization?
        if ($this->authorizationChecker->has($controller) === true) {
            // Resolve the credential the user sent
            try {
                $credential = $this->credentialResolver->resolve();
            } catch (UnresolvableException $e) {
                $this->server->sendResponse(new OrdinaryResponse(
                    null,
                    OrdinaryResponse::HTTP_FORBIDDEN
                ));

                return;
            }

            // Process the credential
            try {
                $token = $this->credentialProcessor->process($credential);
            } catch (InvalidDataException $e) {
                $this->server->sendResponse(new OrdinaryResponse(
                    null,
                    OrdinaryResponse::HTTP_UNAUTHORIZED
                ));

                return;
            }

            // Get the user roles
            $roles = $this->roleCollector->collect($token);
            // Doesn't the user have the correct role for the current controller?
            if ($this->authorizationChecker->check($controller, $roles) === false) {
                $this->server->sendResponse(new OrdinaryResponse(
                    null,
                    OrdinaryResponse::HTTP_UNAUTHORIZED
                ));

                return;
            }
        }

        $controller = $this->createContainer()->get($controller);

        $method = new \ReflectionMethod($controller, '__invoke');
        $parameters = $method->getParameters();
        $artificialParameters = [];
        foreach ($parameters as $parameter) {
            if ($parameter->getName() == 'token') {
                $artificialParameters['token'] = $token;
            } elseif ($parameter->getName() == 'request') {
                $artificialParameters['request'] = $request;
            } elseif (array_key_exists($parameter->getName(), $variables)) {
                $artificialParameters[$parameter->getName()] = $variables[$parameter->getName()];
            }
        }

        /** @var OrdinaryResponse $response */
        $response = call_user_func_array([$controller, '__invoke'], $artificialParameters);

        $this->server->sendResponse(new OrdinaryResponse(
            $response->getContent(),
            $response->getStatus(),
            array_merge(
                $response->getHeaders(),
                [
                    'Access-Control-Allow-Origin' => '*',
                ]
            )
        ));
    }

    /**
     * @return DeductibleContainer
     */
    private function createContainer()
    {
        $bag = $this->resourceLoader->load([
            'dir'    => sprintf('%s/../../../../../../../http', __DIR__),
            'filter' => '*.php',
            'extra'  => [
                'type'       => 'annotation',
                'annotation' => '/^di\\\\controller/',
            ],
        ]);

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
