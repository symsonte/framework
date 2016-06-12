<?php

namespace Symsonte\Call\Parameter\Convertion\Resource;

use Symsonte\Resource\AnnotationFileResource;
use Symsonte\Resource\Normalizer;
use Symsonte\Resource\UnsupportedDataAndResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.call.parameter.resource.file_normalizer', 'symsonte.call.parameter.resource.normalizer']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.call.parameter.resource.file_normalizer', 'symsonte.call.parameter.resource.normalizer']
 * })
 */
class AnnotationFileNormalizer implements Normalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($data, $resource)
    {
        if (!$resource instanceof AnnotationFileResource) {
            throw new UnsupportedDataAndResourceException($data, $resource);
        }

        $data = $data['value'];

        $normalization = new Normalization();

        $normalization->class = $data['metadata']['class'];
        $normalization->method = $data['method'];
        $normalization->parameter = $data['value']['name'];
        $normalization->value = $data['value']['value'];

        return $normalization;
    }
}
