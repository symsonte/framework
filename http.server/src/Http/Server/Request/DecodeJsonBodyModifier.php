<?php

namespace Symsonte\Http\Server\Request;

use Symsonte\Http\Server;
use Symsonte\Http\Server\PostRequest;
use Symsonte\Http\Server\PostRequest\StringField;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.modifier']
 * })
 */
class DecodeJsonBodyModifier implements Modifier
{
    /**
     * {@inheritdoc}
     */
    public function modify($request)
    {
        if (
            $request instanceof PostRequest
            && $request->hasHeader('CONTENT_TYPE')
            && $request->getHeader('CONTENT_TYPE') == 'application/json'
        ) {
            $content = json_decode($request->getBody(), true);

            if (json_last_error() == JSON_ERROR_NONE) {
                foreach ($content as $key => $value) {
                    $request->addField(new StringField($key, $value));
                }
            }
        }

        return $request;
    }
}
