<?php

namespace Symsonte\Http\Response;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.response.body_modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.response.body_modifier']
 * })
 */
class EncodeToJsonBodyModifier implements BodyModifier
{
    /**
     * {@inheritdoc}
     */
    public function modify($status, $headers, $body)
    {
        if ($body === null) {
            return null;
        }

        return json_encode($body);
    }
}
