<?php

namespace Symsonte\Http\Response;

interface Modifier
{
    /**
     * @param mixed $response
     *
     * @return mixed
     */
    public function modify($response);
}
