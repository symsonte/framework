<?php

namespace Symsonte\Cli\Server\Input\Resolution\Resource;

use Symsonte\Cli\Server\Input\Resolution;

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