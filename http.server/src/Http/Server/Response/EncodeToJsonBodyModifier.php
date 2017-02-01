<?php

namespace Symsonte\Http\Server\Response;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.server.response.body_modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.server.response.body_modifier']
 * })
 */
class EncodeToJsonBodyModifier implements BodyModifier
{
    /**
     * {@inheritdoc}
     */
    public function modify($status, $headers, $body)
    {
        return json_encode($body, true);
    }
}
