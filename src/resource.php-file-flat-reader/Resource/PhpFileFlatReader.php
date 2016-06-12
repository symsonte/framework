<?php

namespace Symsonte\Resource;

use Symfony\Component\Yaml\Yaml;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service()
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.resource.flat_reader', 'symsonte.resource.file_flat_reader']
 * })
 */
class PhpFileFlatReader implements FlatReader
{
    /**
     * {@inheritdoc}
     */
    public function read($resource)
    {
        if (!$resource instanceof PhpFileResource) {
            throw new UnsupportedResourceException($resource);
        }

        if (!is_file($resource->getFile())) {
            throw new InvalidResourceException($resource, sprintf('Can not find file "%s".', $resource->getFile()));
        }

        try {
            return require $resource->getFile();
        } catch (\Exception $e) {
            throw new InvalidResourceException($resource, $e->getMessage(), 0, $e);
        }
    }
}
