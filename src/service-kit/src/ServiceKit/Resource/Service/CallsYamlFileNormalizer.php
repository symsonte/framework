<?php

namespace Symsonte\ServiceKit\Resource\Service;

use Symsonte\Resource\Normalizer;
use Symsonte\Resource\YamlFileResource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class CallsYamlFileNormalizer implements Normalizer
{
    /**
     * @param array            $data
     * @param YamlFileResource $resource
     *
     * @return CallNormalization[]
     */
    public function normalize($data, $resource)
    {
        $normalizations = [];
        foreach ($data as $call) {
            $normalization = new CallNormalization();
            $normalization->method = $call[0];
            $normalization->arguments = $call[1];

            $normalizations[] = $normalization;
        }

        return $normalizations;
    }
}
