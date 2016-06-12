<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class FileResource
{
    /**
     * @var string
     */
    private $file;

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
}
