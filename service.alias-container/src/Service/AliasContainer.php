<?php

namespace Symsonte\Service;

use Symsonte\Service\Declaration\AliasStorer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class AliasContainer implements Container
{
    /**
     * @var AliasStorer
     */
    private $storer;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param AliasStorer $storer
     * @param Container   $container
     */
    function __construct(
        AliasStorer $storer,
        Container $container
    )
    {
        $this->storer = $storer;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->container->has($id)
            || $this->storer->has($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if ($this->container->has($id)) {
        } elseif ($this->storer->has($id)) {
            $id = $this->storer->get($id);
        } else {
            throw new NonexistentServiceException($id);
        }

        return $this->container->get($id);
    }
}