<?php

namespace Symsonte\Http;

interface Matcher
{
    /**
     * @param mixed $match
     * @param mixed $request
     *
     * @throws UnsupportedMatchException If the match is not supported.
     *
     * @return bool
     */
    public function match($match, $request);
}
