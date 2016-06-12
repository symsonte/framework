<?php

namespace Symsonte\Http;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.matcher']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.matcher']
 * })
 */
class MethodMatcher implements Matcher
{
    /**
     * {@inheritdoc}
     */
    public function match($match, $request)
    {
        if (!$match instanceof MethodMatch) {
            throw new UnsupportedMatchException($match);
        }

        return in_array('GET', $match->getMethods()) && $request instanceof GetRequest
            || in_array('POST', $match->getMethods()) && $request instanceof PostRequest;
    }
}
