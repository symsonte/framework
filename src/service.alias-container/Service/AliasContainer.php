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
    public function __construct(
        AliasStorer $storer,
        Container $container
    ) {
        $this->storer = $storer;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->container->has($id)
            || $this->container->has($this->generateName($id))
            || $this->storer->has($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if ($this->container->has($id)) {
        }
        // Patch to allow passing full class name as id
        elseif ($this->container->has($this->generateName($id))) {
            $id = $this->generateName($id);
        } elseif ($this->storer->has($id)) {
            $id = $this->storer->get($id);
        } else {
            throw new NonexistentServiceException($id);
        }

        return $this->container->get($id);
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function generateName($class)
    {
        return
            strtolower(
                strtr(
                    preg_replace('/(?<=[a-zA-Z0-9])[A-Z]/', '_\\0', $class),
                    '\\',
                    '.'
                )
            );
    }
}
