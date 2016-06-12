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
class FilesystemStorer implements Storer
{
    /**
     * @var string
     */
    private $dir;

    /**
     * @var string
     */
    private $unique;

    /**
     * @param string $dir
     * @param string $unique
     *
     * @ds\arguments({
     *     dir: '%cache_dir%'
     * })
     *
     * @di\arguments({
     *     dir: '%cache_dir%'
     * })
     */
    public function __construct($dir, $unique = null)
    {
        $this->dir = $dir;
        $this->unique = $unique;
    }

    /**
     * @inheritdoc
     */
    public function add($data, $resource)
    {
        $file = $this->generateFilename($resource);

        file_put_contents($file, serialize($data));
    }

    /**
     * @inheritdoc
     */
    public function has($resource)
    {
        $file = $this->generateFilename($resource);

        return is_file($file);
    }

    /**
     * @inheritdoc
     */
    public function get($resource)
    {
        $file = $this->generateFilename($resource);

        return unserialize(file_get_contents($file));
    }

    /**
     * Generates a filename using dir and given resource.
     *
     * @param  mixed $resource
     *
     * @return string
     */
    private function generateFilename($resource)
    {
        return sprintf(
            "%s/%s%s",
            $this->dir,
            md5(serialize($resource)),
            !is_null($this->unique) ? sprintf(".%s", $this->unique) : ''
        );
    }
}
