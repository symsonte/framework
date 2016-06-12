<?php

namespace Symsonte\Http\Resolution;

use Symsonte\Http\Resolution;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Bag
{
    /**
     * @var Resolution
     */
    private $resolutions;

    /**
     * @param Resolution[]|null $resolutions
     */
    public function __construct($resolutions = [])
    {
        $this->resolutions = $resolutions;
    }

    /**
     * @param Resolution $resolution
     */
    public function add(Resolution $resolution)
    {
        $this->resolutions[$resolution->getKey()] = $resolution;
    }

    /**
     * @param Bag $bag
     */
    public function merge(Bag $bag)
    {
        $this->resolutions = array_merge(
            $this->resolutions,
            $bag->all()
        );
    }

    /**
     * @return Resolution[]
     */
    public function all()
    {
        return $this->resolutions;
    }
}
