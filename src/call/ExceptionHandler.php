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
     * @param array     $namespaces
     *
     * @return array
     *
     * @throws Exception
     */
    public function handle(
        Exception $e,
        array $namespaces
    ) {
        if (!$this->match(
            $e,
            $namespaces
        )) {
            throw $e;
        }

        $payload = null;

        if ($e instanceof JsonSerializable) {
            $payload = $e->jsonSerialize();
        }

        $class = $this->reduce(
            $e,
            $namespaces
        );

        return [
            'code' => $this->generateKey($class),
            'payload' => $payload
        ];
    }

    /**
     * @param Exception $e
     * @param array     $namespaces
     *
     * @return bool
     */
    private function match(
        Exception $e,
        array $namespaces
    ) {
        foreach ($namespaces as $namespace) {
            if (strpos(get_class($e), $namespace) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Exception $e
     * @param array     $namespaces
     *
     * @return string
     */
    private function reduce(
        Exception $e,
        array $namespaces
    ) {
        $class = get_class($e);

        $currentReduced = $class;

        foreach ($namespaces as $namespace) {
            $newReduced = str_replace(
                sprintf("%s\\", $namespace),
                '',
                $class
            );

            if (strlen($newReduced) < strlen($currentReduced)) {
                $currentReduced = $newReduced;
            }
        }

        return $currentReduced;
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
