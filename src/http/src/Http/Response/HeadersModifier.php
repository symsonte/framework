<?php

namespace Symsonte\Http\Response;

interface HeadersModifier
{
    /**
     * @param string $status
     * @param array  $headers
     * @param mixed  $body
     *
     * @return array The new headers
     */
    public function modify($status, $headers, $body);
}
