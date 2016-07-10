<?php

namespace Symsonte\Http\Server\Request\Authentication\Credential;

interface Processor
{
    /**
     * @throws InvalidDataException
     *
     * @return string The token.
     */
    public function process();
}
