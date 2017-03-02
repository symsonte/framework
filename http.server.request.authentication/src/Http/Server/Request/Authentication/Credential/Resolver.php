<?php

namespace Symsonte\Http\Server\Request\Authentication\Credential;

use Symsonte\Http\Server\Request\Authentication\Credential;

interface Resolver
{
    /**
     * @throws UnresolvableException
     *
     * @return Credential
     */
    public function resolve();
}
