<?php

namespace Symsonte\ServiceKit;

use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Installer
{
    /**
     * @param Bag $bag
     *
     * @return Bag
     */
    public function install(Bag $bag);
}
