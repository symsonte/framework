<?php

namespace Symsonte\Http\Server\Request\Authentication\Credential;

use Symsonte\Http\Server\Request\Authentication\Credential;

interface Processor
{
    /**
     * @param Credential $credential
     *
     * @throws InvalidDataException
     *
     * @return string The token.
     */
    public function process(Credential $credential);
}
