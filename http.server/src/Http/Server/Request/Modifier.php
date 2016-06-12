<?php

namespace Symsonte\Http\Server\Request;

use Symsonte\Http\Server\GetRequest;
use Symsonte\Http\Server\PostRequest;

interface Modifier
{
    /**
     * @param GetRequest|PostRequest $request
     *
     * @return GetRequest|PostRequest
     */
    public function modify($request);
}
