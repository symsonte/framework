<?php

namespace Symsonte\Resource;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UnsupportedNormalizationException extends \InvalidArgumentException
{
    /**
     * @var mixed
     */
    private $normalization;

    /**
     * @param mixed $declaration
     */
    public function __construct($declaration)
    {
        $this->normalization = $declaration;

        parent::__construct(sprintf(
            'Normalization %s not supported',
            print_r($declaration, true)
        ));
    }

    /**
     * @return mixed
     */
    public function getNormalization()
    {
        return $this->normalization;
    }
}
