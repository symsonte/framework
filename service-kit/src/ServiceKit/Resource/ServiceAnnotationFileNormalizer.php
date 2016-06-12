<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\AnnotationFileResource;
use Symsonte\Resource\Normalizer;
use Symsonte\Resource\UnsupportedDataAndResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: [{key: 1, name: 'symsonte.service_kit.resource.annotation_file_normalizer'}]
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: [{key: 1, name: 'symsonte.service_kit.resource.annotation_file_normalizer'}]
 * })
 */
class ServiceAnnotationFileNormalizer implements Normalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($data, $resource)
    {
        /** @var AnnotationFileResource $resource */
        if ($resource->getAnnotation() == "/^di\\\\aliases/") {
            throw new UnsupportedDataAndResourceException($data, $resource);
        }

        $declaration = new ServiceNormalization();
        $declaration->id = isset($data['value']['class'][0]['value']['id']) ? $data['value']['class'][0]['value']['id'] : $this->generateName($data['value']['class'][0]['class']);
        $declaration->class = $data['value']['class'][0]['class'];
        $declaration->arguments =
            isset($data['value']['method'][0]['method'])
            && $data['value']['method'][0]['method'] == '__construct'
            && strpos($data['value']['method'][0]['key'], '\arguments') !== false
                ? $data['value']['method'][0]['value']
                : [];
        $declaration->calls = isset($data['value']['class'][0]['value']['calls']) ?
            $data['value']['class'][0]['value']['calls']
            : [];
        $declaration->deductible = isset($data['value']['class'][0]['value']['deductible'])
            ? $data['value']['class'][0]['value']['deductible']
            : true;
        $declaration->private = isset($data['value']['class'][0]['value']['private'])
            ? $data['value']['class'][0]['value']['private']
            : false;
        $declaration->disposable = isset($data['value']['class'][0]['value']['disposable'])
            ? $data['value']['class'][0]['value']['disposable']
            : false;
        $declaration->lazy = isset($data['value']['class'][0]['value']['lazy'])
            ? $data['value']['class'][0]['value']['lazy']
            : false;

        $tags = isset($data['value']['class'][0]['value']['tags'])
            ? $data['value']['class'][0]['value']['tags']
            : [];
        foreach ($tags as $i => $tag) {
            if (is_string($tag)) {
                $tag = [
                    'key' => uniqid(),
                    'name' => $tag
                ];
            }

            if ($tag['key'] == 'last') {
                $tag['key'] = 9999; // Can't use INF because key is used as index on tags array
            }

            $tags[$i] = $tag;
        }
        $declaration->tags = $tags;

        $declaration->circularCalls = isset($data['value']['class'][0]['value']['circularCalls'])
            ? $data['value']['class'][0]['value']['circularCalls']
            : [];

        return $declaration;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function generateName($class)
    {
        return
            strtolower(
                strtr(
                    preg_replace('/(?<=[a-zA-Z0-9])[A-Z]/', '_\\0', $class),
                    '\\',
                    '.'
                )
            );
    }
}
