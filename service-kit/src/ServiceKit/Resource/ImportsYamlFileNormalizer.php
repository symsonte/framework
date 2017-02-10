<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\Normalizer;
use Symsonte\Resource\YamlFileResource;
use Symsonte\Resource\UnsupportedDataAndResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.yaml_file_normalizer']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.service_kit.resource.yaml_file_normalizer']
 * })
 */
class ImportsYamlFileNormalizer implements Normalizer
{
    /**
     * @param array            $data
     * @param YamlFileResource $resource
     *
     * @return ImportsNormalization
     *
     * @throws UnsupportedDataAndResourceException
     */
    public function normalize($data, $resource)
    {
        if (!$this->support($data, $resource)) {
            throw new UnsupportedDataAndResourceException($data, $resource);
        }

        $declaration = new ImportsNormalization();
        $declaration->metadata = $data['value'];

        return $declaration;
    }

    /**
     * @param $data
     * @param $resource
     *
     * @return bool
     */
    private function support($data, $resource)
    {
        if ($data['key'] != 'imports') {
            return false;
        }

        return true;
    }
}