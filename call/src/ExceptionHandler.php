<?php

namespace Symsonte;

use Exception;
use JsonSerializable;

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
class ExceptionHandler
{
    /**
     * @param Exception $e
     * @param string $namespace
     *
     * @return array
     *
     * @throws Exception
     */
    public function handle(
        Exception $e,
        string $namespace
    ) {
        $payload = null;

        if ($e instanceof JsonSerializable) {
            $payload = $e->jsonSerialize();
        }

        if (strpos(get_class($e), $namespace) === false) {
            throw $e;
        }

        $class = get_class($e);

        $class = str_replace(
            sprintf("%s\\", $namespace),
            '',
            $class
        );

        return [
            'code' => $this->generateKey($class),
            'payload' => $payload
        ];
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function generateKey($class)
    {
        return strtolower(
            strtr(
                preg_replace(
                    '/(?<=[a-zA-Z0-9])[A-Z]/',
                    '-\\0',
                    $class
                ),
                '\\',
                '.'
            )
        );
    }
}
