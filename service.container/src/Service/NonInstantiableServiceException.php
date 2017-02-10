<?php

namespace Symsonte\Service;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class NonInstantiableServiceException extends \InvalidArgumentException
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     * @param string $message
     */
    public function __construct($id, $message)
    {
        $this->$id = $id;

        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
