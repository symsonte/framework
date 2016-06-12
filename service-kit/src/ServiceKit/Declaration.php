<?php

namespace Symsonte\ServiceKit;

use Symsonte\Service\Declaration as InternalDeclaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class Declaration
{
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
     * @var string[]
     */
    private $tags;

    /**
     * @var string[]
     */
    private $aliases;

    /**
     * @param InternalDeclaration $declaration
     * @param bool                $deductible
     * @param bool                $private
     * @param bool                $disposable
     * @param string[]            $tags
     * @param string[]            $aliases
     */
    public function __construct(
        InternalDeclaration $declaration,
        $deductible,
        $private,
        $disposable,
        array $tags,
        array $aliases
    )
    {
        $this->declaration = $declaration;
        $this->deductible = $deductible;
        $this->private = $private;
        $this->disposable = $disposable;
        $this->tags = $tags;
        $this->aliases = $aliases;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    public function is($id)
    {
        return $this->declaration->getId() === $id
        || isset($this->aliases[$id]);
    }

    /**
     * @return InternalDeclaration
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
}