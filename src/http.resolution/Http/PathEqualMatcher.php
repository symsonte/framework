<?php

namespace Symsonte\Http;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class PathEqualMatcher implements Matcher
{
    public function support($match)
    {
        return $match instanceof PathMatch;
    }

    /**
     * @param PathMatch               $match
     * @param GetRequest|PostRequest $request
     *
     * @return bool
     */
    public function match($match, $request)
    {
        return (bool) $request->getPath() == $match->getPattern();
    }
}
