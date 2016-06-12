<?php

namespace Symsonte\Http\Request;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.request.modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.request.modifier']
 * })
 */
class EnableCorsModifier implements Modifier
{
    /**
     * {@inheritdoc}
     */
    public function modify($request)
    {
        $request->addHeader('Access-Control-Allow-Origin', '*');
        $request->addHeader('Access-Control-Allow-Credentials', 'true');

        return $request;
    }
}
