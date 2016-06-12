<?php

namespace Symsonte\ServiceKit;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Instance
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var object
     */
    private $object;

    /**
     * @var string[]
     */
    private $tags;

    /**
     * @param string   $id
     * @param object   $object
     * @param string[]|null $tags
     */
    public function __construct(
        string $id,
        object $object,
        array $tags = []
    ) {
        $this->id = $id;
        $this->object = $object;
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return string[]
     */
    public function getTags()
    {
        $tags = [];

        foreach ($this->tags as $key => $value) {
            $tags[] = [
                'key' => $key,
                'name' => $value
            ];
        }

        return $tags;
    }
}
