<?php

namespace Symsonte\Http\Server\Request;

use Zend\Diactoros\Uri;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.uri_modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.uri_modifier']
 * })
 */
class XDebugUriModifier implements UriModifier
{
    /**
     * {@inheritdoc}
     */
    public function modify($method, $uri, $version, $headers, $body)
    {
        $parameters = [];
        parse_str(parse_url($uri, PHP_URL_QUERY), $parameters);
        unset($parameters['XDEBUG_SESSION_START']);

        $uri = new Uri($uri);

        return $uri->withQuery(http_build_query($parameters))->__toString();
    }
}
