<?php

namespace Symsonte\Http\Server\Request\Resolution;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Finder
{
    /**
     * @param $request
     *
     * @return string|false The matched key or false.
     */
    public function first($request);

    /**
     * @param Bag $bag
     */
    public function merge(Bag $bag);
}
