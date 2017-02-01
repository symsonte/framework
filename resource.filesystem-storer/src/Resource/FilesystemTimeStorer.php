<?php

namespace Symsonte\Resource;

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
    private $filesystemStorer;

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
        $this->filesystemStorer = new FilesystemStorer($dir, 'time');
    }

    /**
     * @inheritdoc
     */
    public function add($data, $resource)
    {
        $this->filesystemStorer->add($data, $resource);
    }

    /**
     * @inheritdoc
     */
    public function has($resource)
    {
        return $this->filesystemStorer->has($resource);
    }

    /**
     * @inheritdoc
     */
    public function get($resource)
    {
        return $this->filesystemStorer->get($resource);
    }
}
