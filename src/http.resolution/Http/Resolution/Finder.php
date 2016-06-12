<?php

namespace Symsonte\Http\Resolution;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Finder
{
    /**
     * @param string $method
     * @param string $path
     *
     * @return string|false The matched key or false.
     *
     * @throws NotFoundException
     */
    public function first($method, $path);

    /**
     * @return array
     */
    public function all();

    /**
     * @param Bag $bag
     */
    public function merge(Bag $bag);
}
