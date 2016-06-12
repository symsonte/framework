<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\ServiceKit\Resource\Service\CallNormalization;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ServiceNormalization
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $class;

    /**
     * @var string[]
     */
    public $arguments;

    /**
     * @var CallNormalization[]
     */
    public $calls;

    /**
     * @var boolean
     */
    public $deductible;

    /**
     * @var boolean
     */
    public $private;

    /**
     * @var boolean
     */
    public $disposable;

    /**
     * @var boolean
     */
    public $lazy;

    /**
     * @var string[]
     */
    public $tags;
}