<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\Cacher;
use Symsonte\Resource\Storer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class PerpetualCacher implements Cacher
{
    /**
     * @var Storer
     */
    private $storer;

    /**
     * @param Storer $storer
     *
     * @ds\arguments({
     *     storer:    '@symsonte.service_kit.resource.filesystem_data_storer'
     * })
     *
     * @di\arguments({
     *     storer:    '@symsonte.service_kit.resource.filesystem_data_storer'
     * })
     */
    public function __construct(
        Storer $storer
    ) {
        $this->storer = $storer;
    }

    /**
     * {@inheritdoc}
     */
    public function store($data, $resource)
    {
        $this->storer->add($data, $resource);
    }

    /**
     * {@inheritdoc}
     */
    public function approve($resource)
    {
        return $this->storer->has($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve($resource)
    {
        return $this->storer->get($resource);
    }
}
