<?php

namespace Symsonte\Http\Message;

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
    const KEY_CONTENT_TYPE = 'content-type';
    const KET_ACCESS_CONTROL_ALLOW_ORIGIN = 'access-control-allow-origin';
    const KET_ACCESS_CONTROL_ALLOW_CREDENTIALS = 'access-control-allow-credentials';
    const KET_ACCESS_CONTROL_ALLOW_HEADERS = 'access-control-allow-headers';

    const VALUE_APPLICATION_JSON = 'application/json';

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

        foreach ($headers[$key] as $header) {
            // Find also in composed headers like:
            // content-type: application/json; charset=utf-8
            if (strpos($header, $value) === 0) {
                return true;
            }
        }

        return false;
    }
}
