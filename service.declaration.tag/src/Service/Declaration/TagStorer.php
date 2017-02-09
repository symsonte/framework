<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class TagStorer
{
    /**
     * @var array
     */
    private $ids;

    /**
     * @param array $ids
     */
    public function __construct($ids = [])
    {
        $this->ids = $ids;
    }

    /**
     * Adds an id to a tag.
     *
     * @param string $id
     * @param string $key
     * @param string $tag
     */
    public function add($id, $key, $tag)
    {
        $this->ids[$tag][$key] = $id;
    }

    /**
     * Gets ids from given tag.
     *
     * @param string $tag
     *
     * @return array
     */
    public function get($tag)
    {
        return isset($this->ids[$tag]) ? $this->ids[$tag] : [];
    }

    /**
     * Gets all ids.
     *
     * @return array
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
        $this->ids = array_merge_recursive(
            $this->ids,
            $ids
        );
    }
}
