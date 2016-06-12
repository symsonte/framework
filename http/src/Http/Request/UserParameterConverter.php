<?php

namespace Symsonte\Http\Request;

use Symsonte\Call\Parameter\ConvertionStorer;
use Symsonte\Call\ParameterConverter;
use Symsonte\Authentication;
use Symsonte\Http;
use LogicException;

/**
 * @di\service({
 *     tags: ['symsonte.call.parameter_converter']
 * })
 */
class UserParameterConverter implements ParameterConverter
{
    /**
     * @var ConvertionStorer
     */
    private $convertionStorer;

    /**
     * @var Http\Authentication\CredentialResolver
     */
    private $resolver;

    /**
     * @var Authentication\CredentialProcessor
     */
    private $processor;

    /**
     * @param ConvertionStorer $convertionStorer
     * @param Http\Authentication\CredentialResolver $resolver
     * @param Authentication\CredentialProcessor $processor
     */
    public function __construct(
        ConvertionStorer $convertionStorer, 
        Http\Authentication\CredentialResolver $resolver, 
        Authentication\CredentialProcessor $processor
    ) {
        $this->convertionStorer = $convertionStorer;
        $this->resolver = $resolver;
        $this->processor = $processor;
    }

    /**
     * @param string  $class
     * @param string  $method
     * @param mixed[] $parameters
     *
     * @return array
     */
    public function convert(
        string $class,
        string $method,
        array $parameters
    ) {
        $convertions = [];
        
        $key = $this->convertionStorer->find(
            $class,
            $method,
            "http\\request\\user"
        );

        if (!$key) {
            return $convertions;
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

        $convertions[$key] = $user;

        return $convertions;
    }
}
