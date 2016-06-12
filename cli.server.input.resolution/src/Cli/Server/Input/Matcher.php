<?php

namespace Symsonte\Cli\Server\Input;

interface Matcher
{
    /**
     * @param mixed $match
     * @param mixed $input
     *
     * @return bool
     *
     * @throws UnsupportedMatchException If the match is not supported.
     */
    public function match($match, $input);
}