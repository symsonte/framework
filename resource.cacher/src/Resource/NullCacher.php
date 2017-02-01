<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @di\service({
 *     private: true
 * })
 */
class NullCacher implements Cacher
{
    /**
     * @inheritdoc
     */
    public function store($data, $resource)
    {
    }

    /**
     * @inheritdoc
     */
    public function approve($resource)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function retrieve($resource)
    {
        return null;
    }
}
