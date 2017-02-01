<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\Normalizer;
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
class AliasesYamlFileNormalizer implements Normalizer
{
    /**
     * {@inheritdoc}
     *
     * @return AliasesNormalization
     */
    public function normalize($data, $resource)
    {
        if ($data['key'] != 'aliases') {
            throw new UnsupportedDataAndResourceException($data, $resource);
        }

        $declaration = new AliasesNormalization();
        $declaration->aliases = $data['value'];

        return $declaration;
    }
}