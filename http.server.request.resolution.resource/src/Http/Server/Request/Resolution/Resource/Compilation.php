<?php

namespace Symsonte\Http\Server\Request\Resolution\Resource;

use Symsonte\Http\Server\Request\Resolution;

class Compilation
{
    /**
     * @var Resolution
     */
    private $resolution;

    /**
     * @param Resolution $resolution
     */
    public function __construct(Resolution $resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return Resolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }
}