<?php

namespace Symsonte\Http\Server\Request;

interface Matcher
{
    /**
     * @param mixed $match
     * @param mixed $request
     *
     * @return bool
     *
     * @throws UnsupportedMatchException If the match is not supported.
     */
    public function match($match, $request);
}