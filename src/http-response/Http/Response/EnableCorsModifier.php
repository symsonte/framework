<?php

namespace Symsonte\Http\Response;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.response.modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.response.modifier']
 * })
 */
class EnableCorsModifier implements Modifier
{
    /**
     * {@inheritdoc}
     */
    public function modify($response)
    {
        $response->addHeader('Access-Control-Allow-Origin', '*');
        $response->addHeader('Access-Control-Allow-Credentials', 'true');
        $response->addHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, Device');

        return $response;
    }
}
