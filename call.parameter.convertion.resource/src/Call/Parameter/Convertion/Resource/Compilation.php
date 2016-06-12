<?php

namespace Symsonte\Call\Parameter\Convertion\Resource;

use Symsonte\Call\Parameter\Convertion;

class Compilation
{
    /**
     * @var Convertion
     */
    private $convertion;

    /**
     * @param Convertion $convertion
     */
    public function __construct(Convertion $convertion)
    {
        $this->convertion = $convertion;
    }

    /**
     * @return Convertion
     */
    public function getConvertion()
    {
        return $this->convertion;
    }
}
