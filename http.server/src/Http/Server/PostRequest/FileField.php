<?php

namespace Symsonte\Http\Server\PostRequest;

class FileField implements Field
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var \SplFileObject
     */
    private $file;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $mime;

    /**
     * @var int
     */
    private $size;

    /**
     * @param string         $key
     * @param \SplFileObject $file
     * @param string         $name
     * @param string         $mime
     * @param string         $size
     */
    public function __construct(
        $key,
        \SplFileObject $file,
        $name,
        $mime,
        $size
    ) {
        $this->key = $key;
        $this->file = $file;
        $this->name = $name;
        $this->mime = $mime;
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return \SplFileObject
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
}
