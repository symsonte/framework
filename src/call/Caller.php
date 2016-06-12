<?php

namespace Symsonte;

interface Caller
{
    /**
     * @param object $object
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function call(
        object $object,
        string $method,
        array $parameters
    );
}
