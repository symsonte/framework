<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Builder
{
    /**
     * @return Bag
     */
    public function build(Bag $bag);
}
