<?php

namespace Symsonte\Http\Response;

interface BodyModifier
{
    /**
     * @param string $status
     * @param array  $headers
     * @param mixed  $body
     *
     * @return mixed The new body
     */
    public function modify($status, $headers, $body);
}
