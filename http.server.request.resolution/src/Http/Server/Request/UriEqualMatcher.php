<?php

namespace Symsonte\Http\Server\Request;

use Symsonte\Http\Server\GetRequest;
use Symsonte\Http\Server\PostRequest;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UriEqualMatcher implements Matcher
{
    public function support($match)
    {
        return $match instanceof UriMatch;
    }

    /**
     * @param UriMatch               $match
     * @param GetRequest|PostRequest $request
     *
     * @return bool
     */
    public function match($match, $request)
    {
        return (bool) $request->getUri() == $match->getPattern();
    }
}
