<?php

namespace Symsonte\Http\Server\Response;

use Symsonte\Http\Server;
use Symsonte\Http\Server\OrdinaryResponse;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.server.response.modifier']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.server.response.modifier']
 * })
 */
class EncodeToJsonModifier implements Modifier
{
    /**
     * {@inheritdoc}
     */
    public function modify($response)
    {
        if ($response instanceof OrdinaryResponse) {
            $content = $response->getContent();

            if ($content instanceof \ArrayObject) {
                $content = json_encode($content, true);
            } elseif ($content instanceof \Traversable) {
                $json = '[';
                $i = 0;
                foreach ($content as $value) {
                    // TODO: Move this class to domain logic?
                    if (isset($value['_id'])) {
                        $value['id'] = (string) $value['_id'];
                        unset($value['_id']);
                    }

                    if ($i++ != 0) {
                        $json .= ', ';
                    }
                    $json .= json_encode($value, true);
                }
                $json .= ']';
                $content = $json;
            } else {
                $content = json_encode($content, true);
            }

            $response = new OrdinaryResponse(
                $content,
                $response->getStatus(),
                array_merge(
                    $response->getHeaders(),
                    ['Content-Type' => 'application/json']
                )
            );
        }

        return $response;
    }

    /**
     * @param $array
     *
     * @return array
     */
    private function getValues($array)
    {
        $flat = array();

        foreach ($array as $value) {
            if (is_array($value)) {
                $flat = array_merge($flat, $this->getValues($value));
            } else {
                $flat[] = $value;
            }
        }

        return $flat;
    }
}
