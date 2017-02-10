<?php

namespace Symsonte\Http\Server\Request\Authentication\Credential;

interface Processor
{
    /**
     * @return string The token.
     *
     * @throws InvalidDataException
     */
    public function process();
}