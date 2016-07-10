<?php

namespace Symsonte\Http\Server\Request;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.matcher']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.matcher']
 * })
 */
class UriPregMatcher implements Matcher
{
    /**
     * {@inheritdoc}
     */
    public function match($match, $request)
    {
        if (!$match instanceof UriMatch) {
            throw new UnsupportedMatchException($match);
        }

        return (bool) preg_match(
            sprintf(
                '/%s/',
                str_replace('/', '\/', $match->getPattern())
            ),
            $request->getUri()
        );
    }
}
