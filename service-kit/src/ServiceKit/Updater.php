<?php

namespace Symsonte\ServiceKit;

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
