<?php

namespace Symsonte\Http\Request;

trait PathTrait
{
    /**
     * @var string
     */
    private $path;

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
