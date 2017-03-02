<?php

namespace Symsonte\Http\Server\Request;

interface Monitor
{
    /**
     * @param string $method
     * @param string $uri
     * @param string $version
     * @param array  $headers
     * @param mixed  $body
     *
     * @return array The new headers
     */
    public function modify($method, $uri, $version, $headers, $body);
}
