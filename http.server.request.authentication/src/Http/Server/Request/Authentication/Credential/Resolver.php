<?php

namespace Symsonte\Http\Server\Request\Authentication\Credential;

use Symsonte\Http\Server\Request\Authentication\Credential;

interface Resolver
{
    /**
     * @return Credential
     *
     * @throws UnresolvableException
     */
    public function resolve();
}