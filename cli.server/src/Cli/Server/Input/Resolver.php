<?php

namespace Symsonte\Cli\Server\Input;

interface Resolver
{
    /**
     * @return mixed
     */
    public function resolve();
}
