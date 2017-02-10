<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\ServiceKit\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
interface Updater
{
    /**
     * @param Declaration $declaration
     *
     * @return Declaration
     */
    public function update(Declaration $declaration);
}