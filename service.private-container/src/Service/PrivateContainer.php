<?php

namespace Symsonte\Service;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class PrivateContainer implements Container
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var IdStorer
     */
    private $storer;

    /**
     * @param Container $container
     * @param IdStorer  $storer
     */
    public function __construct(
        Container $container,
        IdStorer $storer
    ) {
        $this->container = $container;
        $this->storer = $storer;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->container->has($id)
            && !$this->storer->has($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NonexistentServiceException($id);
        }

        return $this->container->get($id);
    }
}
