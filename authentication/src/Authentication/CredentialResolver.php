<?php

namespace Symsonte\Authentication;

interface CredentialResolver
{
    /**
     * @throws UnresolvableCredentialException
     *
     * @return Credential
     */
    public function resolve();
}
