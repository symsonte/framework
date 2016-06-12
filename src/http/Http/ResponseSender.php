<?php

namespace Symsonte\Http;

interface ResponseSender
{
    /**
     * @param string $status
     * @param array  $headers
     * @param mixed  $body
     */
    public function send($status, $headers, $body);
}
