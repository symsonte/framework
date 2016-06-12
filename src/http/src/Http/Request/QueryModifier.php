<?php

namespace Symsonte\Http\Request;

interface QueryModifier
{
    /**
     * @param string $method
     * @param string $path
     * @param string $query
     * @param string $version
     * @param array  $headers
     * @param mixed  $body
     *
     * @return mixed The new query
     */
    public function modify($method, $path, $query, $version, $headers, $body);
}
