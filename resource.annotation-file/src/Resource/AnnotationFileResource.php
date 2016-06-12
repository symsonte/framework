<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class AnnotationFileResource extends FileResource
{
    /**
     * @var string|null
     */
    private $annotation;

    /**
     * @param string      $file
     * @param string|null $annotation
     */
    public function __construct($file, $annotation = null)
    {
        parent::__construct($file);

        $this->annotation = $annotation;
    }

    /**
     * @return string
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }
}
