<?php

namespace Symsonte\Http\Request;

use Symsonte\Http\Message\HeaderSearcher;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.request.headers_modifier']
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
    public function modify($method, $path, $query, $version, $headers, $body)
    {
        $headers[HeaderSearcher::KET_ACCESS_CONTROL_ALLOW_ORIGIN] = '*';
        $headers[HeaderSearcher::KET_ACCESS_CONTROL_ALLOW_CREDENTIALS] = 'true';

        return $headers;
    }
}
