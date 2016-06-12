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
     * @var bool
     */
    public $deductible;

    /**
     * @var bool
     */
    public $private;

    /**
     * @var bool
     */
    public $disposable;

    /**
     * @var bool
     */
    public $lazy;

    /**
     * @var array
     */
    public $tags;

    /**
     * @var CallNormalization[]
     */
    public $circularCalls;
}
