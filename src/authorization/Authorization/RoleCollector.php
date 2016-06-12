<?php

namespace Symsonte\Authorization;

interface RoleCollector
{
    /**
     * @param string $user
     *
     * @return string[] The roles.
     */
    public function collect($user);
}
