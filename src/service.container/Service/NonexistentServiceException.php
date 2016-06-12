<?php

namespace Symsonte\Service;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonexistentServiceException extends \InvalidArgumentException
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;

        parent::__construct(sprintf('Service with id "%s" does not exist.', $id));
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
