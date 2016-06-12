<?php

namespace Symsonte\Resource;

class DirResource
{
    /**
     * @var string
     */
    private $dir;

    /**
     * @var string
     */
    private $filter;

    /**
     * @var int
     */
    private $depth;

    /**
     * @var array
     */
    private $extra;

    /**
     * @param string      $dir
     * @param string|null $filter
     * @param int|null    $depth
     * @param array       $extra
     */
    public function __construct($dir, $filter = null, $depth = null, $extra = [])
    {
        $this->dir = $dir;
        $this->filter = $filter;
        $this->depth = $depth;
        $this->extra = $extra;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }
}
