<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\Resource\DirResource;
use Symsonte\ServiceKit\Declaration\Bag;
use Symsonte\ServiceKit\Resource\Loader;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DirsBuilder implements Builder
{
    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var string[]
     */
    private $dirs;

    /**
     * @param Loader $loader
     * @param string $dirs
     */
    public function __construct(Loader $loader, $dirs)
    {
        $this->loader = $loader;
        $this->dirs = $dirs;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $bag = new Bag();

        foreach ($this->dirs as $dir) {
            $bag = new Bag(
                array_merge(
                    $bag->getDeclarations(),
                    $this->loader->load(new DirResource(
                        $dir,
                        '*.php',
                        null,
                        [
                            'type'       => 'annotation',
                            'annotation' => '/^di\\\\/',
                        ]
                    ))->getDeclarations()
                )
            );
        }

        return $bag;
    }
}
