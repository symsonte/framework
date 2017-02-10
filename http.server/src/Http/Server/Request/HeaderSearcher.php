<?php

namespace Symsonte\Http\Server\Request;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class HeaderSearcher
{
    CONST KEY_CONTENT_TYPE = 'content-type';
    CONST KET_ACCESS_CONTROL_ALLOW_ORIGIN = 'access-control-allow-origin';
    CONST KET_ACCESS_CONTROL_ALLOW_CREDENTIALS = 'access-control-allow-credentials';
    CONST KET_ACCESS_CONTROL_ALLOW_HEADERS = 'access-control-allow-headers';

    CONST VALUE_APPLICATION_JSON = 'application/json';
    
    /**
     * @param array  $headers
     * @param string $key
     * @param string $value
     * 
     * @return bool
     */
    public function has($headers, $key, $value)
    {
        if (!isset($headers[$key])) {
            return false;
        }

        return in_array($value, $headers[$key]);
    }
}
