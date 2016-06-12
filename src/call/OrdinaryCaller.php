<?php

namespace Symsonte;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     private: true
 * })
 */
class OrdinaryCaller implements Caller
{
    /**
     * {@inheritdoc}
     */
    public function call(
        object $object,
        string $method,
        array $parameters
    ) {
        $result = call_user_func_array([$object, $method], $parameters);

        return $result;
    }
}
