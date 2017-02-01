<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class IdStorer
{
    /**
     * @var string[]
     */
    private $ids;

    /**
     * @param string[] $ids
     */
    public function __construct($ids = [])
    {
        $this->ids = $ids;
    }

    /**
     * @param string $id
     */
    public function add($id)
    {
        $this->ids[$id] = true;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id)
    {
        return isset($this->ids[$id]);
    }

    /**
     * @return string[]
     */
    public function all()
    {
        return $this->ids;
    }

    /**
     * @param string[] $ids
     */
    public function merge($ids)
    {
        $this->ids = array_merge(
            $this->ids,
            $ids
        );
    }
}
