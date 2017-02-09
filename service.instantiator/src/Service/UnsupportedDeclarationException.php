<?php

namespace Symsonte\Service;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class UnsupportedDeclarationException extends \InvalidArgumentException
{
    /**
     * @var Declaration
     */
    private $declaration;

    /**
     * @param Declaration $declaration
     */
    public function __construct(Declaration $declaration)
    {
        $this->declaration = $declaration;
    }

    /**
     * @return Declaration
     */
    public function getDeclaration()
    {
        return $this->declaration;
    }
}
