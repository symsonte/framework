<?php

namespace Symsonte\Http\Server\Response;

interface Modifier
{
    /**
     * @param mixed $response
     *
     * @return mixed
     */
    public function modify($response);
}
