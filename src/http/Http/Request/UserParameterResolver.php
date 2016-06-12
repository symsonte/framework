<?php

namespace Symsonte\Http\Request;

use Symsonte\Call\Parameter\ResolutionStorer;
use Symsonte\Call\ParameterResolver;
use Symsonte\Authentication;
use LogicException;

/**
 * @di\service({
 *     tags: ['symsonte.call.parameter_resolver']
 * })
 */
class UserParameterResolver implements ParameterResolver
{
    /**
     * @var ResolutionStorer
     */
    private $resolutionStorer;

    private ?Authentication\CredentialResolver $resolver;

    /**
     * @var Authentication\CredentialProcessor
     */
    private $processor;

    /**
     * @param ResolutionStorer                   $resolutionStorer
     * @param ?Authentication\CredentialResolver $resolver
     * @param Authentication\CredentialProcessor $processor
     */
    public function __construct(
        ResolutionStorer $resolutionStorer,
        ?Authentication\CredentialResolver $resolver,
        Authentication\CredentialProcessor $processor
    ) {
        $this->resolutionStorer = $resolutionStorer;
        $this->resolver = $resolver;
        $this->processor = $processor;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(
        string $class,
        string $method,
        array $parameters
    ) {
        $conversions = [];

        $key = $this->resolutionStorer->find(
            $class,
            $method,
            "http\\request\\user"
        );

        if (!$key) {
            return $conversions;
        }

        try {
            $credential = $this->resolver->resolve();
        } catch (Authentication\UnresolvableCredentialException $e) {
            throw new LogicException(null, null, $e);
        }

        try {
            $user = $this->processor->process($credential, 'user');
        } catch (Authentication\UnprocessableCredentialException $e) {
            throw new LogicException(null, null, $e);
        }

        $conversions[$key] = $user;

        return $conversions;
    }
}
