<?php

namespace Symsonte\Http\Server\Request;

interface UriModifier
{
    /**
     * @param string $method
     * @param string $uri
     * @param string $version
     * @param array  $headers
     * @param mixed  $body
     *
     * @return mixed The new uri
     */
    public function modify($method, $uri, $version, $headers, $body);
}
