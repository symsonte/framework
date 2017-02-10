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
class DirBuilder implements Builder
{
    /**
     * {@inheritdoc}
     *
     * @return DirResource
     */
    public function build($metadata)
    {
        $metadata = $this->fixMetadata($metadata);

        if (!$this->support($metadata)) {
            throw new UnsupportedMetadataException($metadata);
        }

        $filter = isset($metadata['filter']) ? $metadata['filter'] : null;
        $depth = isset($metadata['depth']) ? $metadata['depth'] : null;
        $extra = isset($metadata['extra']) ? $metadata['extra'] : [];

        return new DirResource($metadata['dir'], $filter, $depth, $extra);
    }

    /**
     * @param $metadata
     *
     * @return bool
     */
    private function support($metadata)
    {
        if (!isset($metadata['dir'])) {
            return false;
        }

        return true;
    }

    /**
     * Fixes metadata if it comes as short version.
     *
     * @param array $metadata
     * @return array
     */
    private function fixMetadata($metadata)
    {
        if (is_string($metadata) && is_dir($metadata)) {
            $metadata = ['dir' => $metadata];
        }

        return $metadata;
    }
}