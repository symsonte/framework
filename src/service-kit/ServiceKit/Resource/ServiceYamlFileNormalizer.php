<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\Normalizer;
use Symsonte\Resource\UnsupportedDataAndResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: [{key: 1, name: 'symsonte.service_kit.resource.yaml_file_normalizer'}]
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: [{key: 1, name: 'symsonte.service_kit.resource.yaml_file_normalizer'}]
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
        $declaration->deductible = isset($data['value']['deductible'])
            ? $data['value']['deductible']
            : true;
        $declaration->private = isset($data['value']['private']) ? $data['value']['private'] : false;
        $declaration->disposable = isset($data['value']['disposable']) ? $data['value']['disposable'] : false;
        $declaration->lazy = isset($data['value']['lazy']) ? $data['value']['lazy'] : false;

        $tags = isset($data['value']['tags']) ? $data['value']['tags'] : [];
        foreach ($tags as $i => $tag) {
            if (is_string($tag)) {
                $tags[$i] = [
                    'key' => uniqid(),
                    'name' => $tag
                ];
            }
        }
        $declaration->tags = $tags;

        $declaration->circularCalls = isset($data['value']['circularCalls']) ? $data['value']['circularCalls'] : [];

        return $declaration;
    }
}
