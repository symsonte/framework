<?php

namespace Symsonte\Http\Server\Request;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     deductible: true,
 *     tags: ['symsonte.http.server.request.body_modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     deductible: true,
 *     tags: ['symsonte.http.server.request.body_modifier']
 * })
 */
class DecodeJsonBodyModifier implements BodyModifier
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
    public function modify($method, $uri, $version, $headers, $body)
    {
        if (
            $this->headerSearcher->has(
                $headers,
                HeaderSearcher::KEY_CONTENT_TYPE,
                HeaderSearcher::VALUE_APPLICATION_JSON
            )
        ) {
            $decodedBody = json_decode($body, true);

            if (json_last_error() == JSON_ERROR_NONE) {
                $body = $decodedBody;
            }
        }

        return $body;
    }
}
