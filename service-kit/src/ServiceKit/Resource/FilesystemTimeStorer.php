<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\FilesystemStorer;
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
class FilesystemTimeStorer implements Storer
{
    /**
     * @var FilesystemStorer
     */
    private $storer;

    /**
     * @param string $dir
     *
     * @ds\arguments({
     *     dir: '%cache_dir%'
     * })
     *
     * @di\arguments({
     *     dir: '%cache_dir%'
     * })
     */
    public function __construct($dir)
    {
        $this->storer = new FilesystemStorer($dir, 'time');
    }

    /**
     * {@inheritdoc}
     */
    public function add($data, $resource)
    {
        $this->storer->add($data, $resource);
    }

    /**
     * {@inheritdoc}
     */
    public function has($resource)
    {
        return $this->storer->has($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function get($resource)
    {
        return $this->storer->get($resource);
    }
}
