<?php

namespace Symsonte\Resource;

class RelativeFileToAbsoluteFileTransformer implements Transformer
{
    /**
     * @var \Symsonte\Resource\Builder
     */
    private $builder;

    /**
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function support($resource, $parentResource = null)
    {
        if (!$resource instanceof FileResource) {
            return false;
        }

        if (!$parentResource instanceof FileResource) {
            return false;
        }

        return true;
    }

    /**
     * @param FileResource $resource
     * @param FileResource $parentResource
     *
     * @return mixed
     */
    public function transform($resource, $parentResource = null)
    {
        $file = sprintf(
            '%s/%s',
            dirname($parentResource->getFile()),
            $resource->getFile()
        );

        return $this->builder->build([
            'file' => $file,
        ]);
    }
}
