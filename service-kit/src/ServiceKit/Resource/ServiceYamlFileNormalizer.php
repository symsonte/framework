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
class ServiceYamlFileNormalizer implements Normalizer
{
    /**
     * {@inheritdoc}
     *
     * @return ServiceNormalization
     */
    public function normalize($data, $resource)
    {
        if (!isset($data['value']['class'])) {
            throw new UnsupportedDataAndResourceException($data, $resource);
        }

        $declaration = new ServiceNormalization();
        $declaration->id = $data['key'];
        $declaration->class = $data['value']['class'];
        $declaration->arguments = isset($data['value']['arguments']) ? $data['value']['arguments'] : [];
        $declaration->calls = isset($data['value']['calls']) ? $data['value']['calls'] : [];
        $declaration->deductible = isset($data['value']['deductible']) ? $data['value']['deductible'] : false;
        $declaration->private = isset($data['value']['private']) ? $data['value']['private'] : false;
        $declaration->disposable = isset($data['value']['disposable']) ? $data['value']['disposable'] : false;
        $declaration->lazy = isset($data['value']['lazy']) ? $data['value']['lazy'] : false;
        $declaration->tags = isset($data['value']['tags']) ? $data['value']['tags'] : [];

        return $declaration;
    }
}
