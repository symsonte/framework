<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.resource.builder']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.resource.builder']
 * })
 */
class AnnotationFileBuilder implements Builder
{
    /**
     * {@inheritdoc}
     *
     * @return AnnotationFileResource
     */
    public function build($metadata)
    {
        if (!$this->support($metadata)) {
            throw new UnsupportedMetadataException($metadata);
        }

        if (isset($metadata['annotation'])) {
            return new AnnotationFileResource($metadata['file'], $metadata['annotation']);
        }

        return new AnnotationFileResource($metadata['file']);
    }

    /**
     * @param $metadata
     *
     * @return bool
     */
    private function support($metadata)
    {
        if (is_string($metadata)) {
            $metadata = ['file' => $metadata];
        }

        if (isset($metadata['file'] )
            && isset($metadata['type'])
            && $metadata['type'] == 'annotation'
        ) {
            return true;
        }

        return false;
    }
}