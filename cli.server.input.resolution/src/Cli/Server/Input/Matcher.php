<?php

namespace Symsonte\Cli\Server\Input;

interface Matcher
{
    /**
     * @param mixed $match
     * @param mixed $input
     *
     * @throws UnsupportedMatchException If the match is not supported.
     *
     * @return bool
     */
    public function match($match, $input);
}
