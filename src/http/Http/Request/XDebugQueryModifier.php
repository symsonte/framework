<?php

namespace Symsonte\Http\Request;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.request.query_modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.request.query_modifier']
 * })
 */
class XDebugQueryModifier implements QueryModifier
{
    /**
     * {@inheritdoc}
     */
    public function modify($method, $path, $query, $version, $headers, $body)
    {
        $parameters = [];
        parse_str($query, $parameters);
        unset($parameters['XDEBUG_SESSION_START']);

        return http_build_query($parameters);
    }
}
