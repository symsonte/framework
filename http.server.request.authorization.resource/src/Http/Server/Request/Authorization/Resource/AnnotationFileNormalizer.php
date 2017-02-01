<?php

namespace Symsonte\Http\Server\Request\Authorization\Resource;

use Symsonte\Resource\AnnotationFileResource;
use Symsonte\Resource\Normalizer;
use Symsonte\Resource\UnsupportedDataAndResourceException;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.authorization.resource.file_normalizer', 'symsonte.http.server.request.authorization.resource.normalizer']
 * })
 *
 * @di\service({
 *     private: true,
 *     tags: ['symsonte.http.server.request.authorization.resource.file_normalizer', 'symsonte.http.server.request.authorization.resource.normalizer']
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

        $normalization->key = $this->generateKey($data['metadata']['class'], $data['method']);

        if (isset($data['value']['roles'])) {
            $normalization->roles = $data['value']['roles'];
        }

        return $normalization;
    }

    /**
     * @param string $class
     * @param string $method
     *
     * @return string
     */
    private function generateKey($class, $method)
    {
        return sprintf(
            '%s:%s',
            strtolower(
                strtr(
                    preg_replace('/(?<=[a-zA-Z0-9])[A-Z]/', '_\\0', $class),
                    '\\',
                    '.'
                )
            ),
            $method
        );
    }
}
