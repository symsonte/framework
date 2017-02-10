<?php

namespace Symsonte\ServiceKit\Resource;

use Symsonte\Service\ConstructorDeclaration;
use Symsonte\Service\Declaration\Call;
use Symsonte\Service\Declaration;

class ServiceCompilation
{
    /**
     * @var ConstructorDeclaration
     */
    private $declaration;

    /**
     * @var boolean
     */
    private $deductible;

    /**
     * @var boolean
     */
    private $private;

    /**
     * @var boolean
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
     * @param boolean     $private
     * @param bool        $disposable
     * @param array       $tags
     * @param Call[]      $circularCalls
     */
    function __construct(
        Declaration $declaration,
        $deductible = false,
        $private = false,
        $disposable = false,
        array $tags = [],
        array $circularCalls = []
    )
    {
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
     * @return boolean
     */
    public function isDeductible()
    {
        return $this->deductible;
    }

    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * @return boolean
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