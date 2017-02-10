<?php

namespace Symsonte\Http\Server\Response;

interface Sender
{
    /**
     * @param string $status
     * @param array  $headers
     * @param mixed  $body
     */
    public function send($status, $headers, $body);
}
