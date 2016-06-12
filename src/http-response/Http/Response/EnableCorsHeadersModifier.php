<?php

namespace Symsonte\Http\Response;

use Symsonte\Http\Message\HeaderSearcher;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.response.headers_modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.response.headers_modifier']
 * })
 */
class EnableCorsHeadersModifier implements HeadersModifier
{
    /**
     * @var HeaderSearcher
     */
    private $headerSearcher;

    /**
     * @param HeaderSearcher $headerSearcher
     */
    public function __construct(HeaderSearcher $headerSearcher)
    {
        $this->headerSearcher = $headerSearcher;
    }

    /**
     * {@inheritdoc}
     */
    public function modify($status, $headers, $body)
    {
        $headers[HeaderSearcher::KEY_ACCESS_CONTROL_ALLOW_ORIGIN] = '*';
        $headers[HeaderSearcher::KEY_ACCESS_CONTROL_ALLOW_CREDENTIALS] = 'true';
        $headers[HeaderSearcher::KEY_ACCESS_CONTROL_ALLOW_HEADERS] = 'Content-Type, Authorization';

        return $headers;
    }
}
