<?php

namespace Symsonte\Call;

interface ParameterConverter
{
    /**
     * @param string   $class
     * @param string   $method
     * @param mixed[]  $parameters
     *
     * @return array
     */
    public function convert(string $class, string $method, array $parameters);
}
