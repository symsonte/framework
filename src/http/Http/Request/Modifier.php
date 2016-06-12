<?php

namespace Symsonte\Http\Request;

use Symsonte\Http\GetRequest;
use Symsonte\Http\PostRequest;

interface Modifier
{
    /**
     * @param GetRequest|PostRequest $request
     *
     * @return GetRequest|PostRequest
     */
    public function modify($request);
}
