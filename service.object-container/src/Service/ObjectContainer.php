<?php

namespace Symsonte\Service;

class ObjectContainer implements Container
{
    /**
     * @var ObjectStorer
     */
    private $storer;

    /**
     * @param ObjectStorer $storer
     */
    public function __construct(ObjectStorer $storer)
    {
        $this->storer = $storer;
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

        return $this->storer->get($id);
    }

    /**
     * @param string $id
     * @param object $service
     */
    public function add($id, $service)
    {
        $this->storer->add($id, $service);
    }
}
