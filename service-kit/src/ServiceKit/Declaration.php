<?php

namespace Symsonte\ServiceKit;

use Symsonte\Service\Declaration as InternalDeclaration;
use Symsonte\Service\Declaration\Call;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Declaration
{
    const IS_DEDUCTIBLE = true;
    const IS_NOT_DEDUCTIBLE = false;
    const IS_PRIVATE = true;
    const IS_NOT_PRIVATE = false;
    const IS_DISPOSABLE = true;
    const IS_LAZY = true;
    const IS_NOT_DISPOSABLE = false;
    const WITHOUT_TAGS = [];
    const WITHOUT_ALIASES = [];
    const WITHOUT_CIRCULAR_CALLS = [];

    /**
     * @var InternalDeclaration
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
     * @var bool
     */
    private $lazy;
    
    /**
     * @var string[]
     */
    private $tags;

    /**
     * @var string[]
     */
    private $aliases;

    /**
     * @var Call[]
     */
    private $circularCalls;

    /**
     * @param InternalDeclaration $declaration
     * @param bool                $deductible
     * @param bool                $private
     * @param bool                $disposable
     * @param bool                $lazy
     * @param string[]            $tags
     * @param string[]            $aliases
     * @param Call[]              $circularCalls
     */
    public function __construct(
        InternalDeclaration $declaration,
        $deductible,
        $private,
        $disposable,
        $lazy,
        array $tags,
        array $aliases,
        array $circularCalls
    ) {
        $this->declaration = $declaration;
        $this->deductible = $deductible;
        $this->private = $private;
        $this->disposable = $disposable;
        $this->lazy = $lazy;
        $this->tags = $tags;
        $this->aliases = $aliases;
        $this->circularCalls = $circularCalls;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function is($id)
    {
        return $this->declaration->getId() === $id
        || isset($this->circularCalls[$id]);
    }

    /**
     * @return InternalDeclaration
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
     * @return bool
     */
    public function isLazy()
    {
        return $this->lazy;
    }
    
    /**
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string[]
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * @return Call[]
     */
    public function getCircularCalls()
    {
        return $this->circularCalls;
    }
}
