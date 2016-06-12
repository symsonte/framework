<?php

namespace Symsonte\Http\Resolution\Resource;

use Symsonte\Http\Resolution;

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
