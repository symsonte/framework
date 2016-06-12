<?php

namespace Symsonte\Http\Authentication;

use Symsonte\Authentication;
use Symsonte\Http;
use LogicException;

/**
 * @di\service({
 *     private: true
 * })
 */
class CredentialProcessor
{
    /**
     * @var Authentication\CredentialResolver
     */
    private $resolver;

    /**
     * @var Authentication\CredentialProcessor
     */
    private $processor;

    /**
     * @param Authentication\CredentialResolver $resolver
     * @param Authentication\CredentialProcessor $processor
     */
    public function __construct(Authentication\CredentialResolver $resolver, Authentication\CredentialProcessor $processor)
    {
        $this->resolver = $resolver;
        $this->processor = $processor;
    }

    /**
     * @return mixed
     */
    public function process()
    {
        try {
            $credential = $this->resolver->resolve();
        } catch (Authentication\UnresolvableCredentialException $e) {
            throw new LogicException(null, null, $e);
        }

        try {
            return $this->processor->process($credential, 'user');
        } catch (Authentication\UnprocessableCredentialException $e) {
            throw new LogicException(null, null, $e);
        }
    }
}
