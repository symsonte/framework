<?php

namespace Symsonte\Http\Server\Response;

use Symsonte\Http\Server\Request\HeaderSearcher;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.server.response.headers_modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.server.response.headers_modifier']
 * })
 */
class EncodeToJsonHeadersModifier implements HeadersModifier
{
    /**
     * {@inheritdoc}
     */
    public function modify($status, $headers, $header)
    {
        $headers[HeaderSearcher::KEY_CONTENT_TYPE] = HeaderSearcher::VALUE_APPLICATION_JSON;

        return $headers;
    }
}
