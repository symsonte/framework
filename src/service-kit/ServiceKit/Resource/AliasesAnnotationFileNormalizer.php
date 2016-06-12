<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Resource\Normalizer;
use Symsonte\Resource\UnsupportedDataAndResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: [{key: 2, name: 'symsonte.service_kit.resource.annotation_file_normalizer'}]
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: [{key: 2, name: 'symsonte.service_kit.resource.annotation_file_normalizer'}]
 * })
 */
class AliasesAnnotationFileNormalizer implements Normalizer
{
    /**
     * {@inheritdoc}
     *
     * @return AliasesNormalization
     */
    public function normalize($data, $resource)
    {
        if ($resource->getAnnotation() != "/^di\\\\aliases/") {
            throw new UnsupportedDataAndResourceException($data, $resource);
        }

        $declaration = new AliasesNormalization();
        $id = isset($data['value']['class'][0]['value']['id']) ? $data['value']['class'][0]['value']['id'] : $this->generateName($data['value']['class'][0]['class']);
        $aliases = $data['value']['class'][0]['value'];

        foreach ($aliases as $alias) {
            $declaration->aliases[$alias] = $id;
        }

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
