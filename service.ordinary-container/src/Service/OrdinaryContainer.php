<?php

namespace Symsonte\Service;

use Symsonte\Service\Declaration\Storer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class OrdinaryContainer implements Container
{
    /**
     * @var Storer
     */
    private $storer;

    /**
     * @var Instantiator
     */
    private $instantiator;

    /**
     * @var array
     */
    private $instantiating = [];

    /**
     * @param Storer       $storer
     * @param Instantiator $instantiator
     */
    function __construct(
        Storer $storer,
        Instantiator $instantiator
    )
    {
        $this->storer = $storer;
        $this->instantiator = $instantiator;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->storer->has($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!$this->storer->has($id)) {
            throw new NonexistentServiceException($id);
        }

        if (isset($this->instantiating[$id])) {
            throw new NonInstantiableServiceException(
                $id,
                sprintf("Circular reference instantiating the service \"%s\".", $id)
            );
        }

        $this->instantiating[$id] = true;

        $instance = $this->instantiator->instantiate($this->storer->get($id));

        unset($this->instantiating[$id]);

        return $instance;
    }
}