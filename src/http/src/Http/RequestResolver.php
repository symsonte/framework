<?php

namespace Symsonte\Http;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface RequestResolver
{
    /**
     * @return string
     */
    public function resolveMethod();

    /**
     * @return string
     */
    public function resolvePath();

    /**
     * @return string
     */
    public function resolveQuery();

    /**
     * @return string
     */
    public function resolveVersion();

    /**
     * @return array
     */
    public function resolveHeaders();

    /**
     * @return mixed
     */
    public function resolveBody();

    /**
     * @return mixed
     */
    public function resolveParsedBody();

    /**
     * @return string
     */
    public function resolveIp();
}
