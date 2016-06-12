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
class PathPregMatcher implements Matcher
{
    /**
     * {@inheritdoc}
     */
    public function match($match, $request)
    {
        if (!$match instanceof PathMatch) {
            throw new UnsupportedMatchException($match);
        }

        return (bool) preg_match(
            sprintf(
                '/%s/',
                str_replace('/', '\/', $match->getPattern())
            ),
            $request->getPath()
        );
    }
}
