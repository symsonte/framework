<?php

namespace Symsonte\Cli\Server\Input\Resolution;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Finder
{
    /**
     * @param $input
     *
     * @return string|false The matched key or false.
     */
    public function first($input);

    /**
     * @param Bag $bag
     */
    public function merge(Bag $bag);
}
