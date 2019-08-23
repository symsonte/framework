<?php

namespace Symsonte\Call;

interface ParameterResolver
{
    /**
     * @param string   $class
     * @param string   $method
     * @param mixed[]  $parameters
     *
     * @return array
     */
    public function resolve(string $class, string $method, array $parameters);
}
