<?php

namespace Symsonte\Http\Server\Request;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Resolver
{
    /**
     * @return string
     */
    public function resolveMethod();

    /**
     * @return string
     */
    public function resolveUri();

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
}