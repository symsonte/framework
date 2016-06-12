<?php

namespace Symsonte\Http\Server\Request\Authentication\Credential;

use Symsonte\Http\Server\Request\Authentication\Credential;

interface Processor
{
    /**
     * @param Credential $credential
     *
     * @return string The token.
     *
     * @throws InvalidDataException
     */
    public function process(Credential $credential);
}