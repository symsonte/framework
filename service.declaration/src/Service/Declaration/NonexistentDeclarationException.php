<?php

namespace Symsonte\Service\Declaration;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonexistentDeclarationException extends \InvalidArgumentException
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    function __construct($id)
    {
        $this->id = $id;

        parent::__construct(sprintf("Declaration with id \"%s\" does not exist."));
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}