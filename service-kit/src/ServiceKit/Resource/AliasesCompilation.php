<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Service\Declaration;

class AliasesCompilation
{
    /**
     * @var array
     */
    private $aliases = [];

    /**
     * @param array $aliases
     */
    function __construct($aliases = [])
    {
        $this->aliases = $aliases;
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }
}