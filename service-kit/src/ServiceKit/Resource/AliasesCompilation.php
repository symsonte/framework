<?php

namespace Symsonte\ServiceKit\Resource;

class AliasesCompilation
{
    /**
     * @var array
     */
    private $aliases = [];

    /**
     * @param array $aliases
     */
    public function __construct($aliases = [])
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
