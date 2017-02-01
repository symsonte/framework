<?php

namespace Symsonte\Http\Server\Response;

use Symsonte\Http\Server;
use Symsonte\Http\Server\Request\HeaderSearcher;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     deductible: true,
 *     tags: ['symsonte.http.server.response.headers_modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     deductible: true,
 *     tags: ['symsonte.http.server.response.headers_modifier']
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
        $headers[HeaderSearcher::KET_ACCESS_CONTROL_ALLOW_ORIGIN] = '*';
        $headers[HeaderSearcher::KET_ACCESS_CONTROL_ALLOW_CREDENTIALS] = 'true';
        $headers[HeaderSearcher::KET_ACCESS_CONTROL_ALLOW_HEADERS] = 'Content-Type, Authorization';

        return $headers;
    }
}
