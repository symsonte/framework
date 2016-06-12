<?php

namespace Symsonte\Http\Server\Request\Authorization\Role;

interface Collector
{
    /**
     * @param string $uniqueness
     *
     * @return string[] The roles.
     */
    public function collect($uniqueness);
}