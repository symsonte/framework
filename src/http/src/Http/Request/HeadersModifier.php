<?php

namespace Symsonte\Http\Request;

interface HeadersModifier
{
    /**
     * @param string $method
     * @param string $path
     * @param array  $query
     * @param string $version
     * @param array  $headers
     * @param mixed  $body
     *
     * @return array The new headers
     */
    public function modify($method, $path, $query, $version, $headers, $body);
}
