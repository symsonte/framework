<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Service\ConstructorDeclaration;
use Symsonte\Service\Declaration;
use Symsonte\Service\Declaration\Call;

class ServiceCompilation
{
    /**
     * @var ConstructorDeclaration
     */
    private $declaration;

    /**
     * @var bool
     */
    private $deductible;

    /**
     * @var bool
     */
    private $private;

    /**
     * @var bool
     */
    private $disposable;

    /**
     * @var array
     */
    private $tags;

    /**
     * @var Call[]
     */
    private $circularCalls;

    /**
     * @param Declaration $declaration
     * @param bool        $deductible
     * @param bool        $private
     * @param bool        $disposable
     * @param array       $tags
     * @param Call[]      $circularCalls
     */
    public function __construct(
        Declaration $declaration,
        $deductible = false,
        $private = false,
        $disposable = false,
        array $tags = [],
        array $circularCalls = []
    ) {
        $this->declaration = $declaration;
        $this->deductible = $deductible;
        $this->private = $private;
        $this->disposable = $disposable;
        $this->tags = $tags;
        $this->circularCalls = $circularCalls;
    }

    /**
     * @return Declaration
     */
    public function getDeclaration()
    {
        return $this->declaration;
    }

    /**
     * @return bool
     */
    public function isDeductible()
    {
        return $this->deductible;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * @return bool
     */
    public function isDisposable()
    {
        return $this->disposable;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return Call[]
     */
    public function getCircularCalls()
    {
        return $this->circularCalls;
    }
}
