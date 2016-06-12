<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\Resource\DirResource;
use Symsonte\ServiceKit\Declaration\Bag;
use Symsonte\ServiceKit\Resource\CachedLoader;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class DirsBuilder implements Builder
{
    /**
     * @var CachedLoader
     */
    private $loader;

    /**
     * @var string[]
     */
    private $dirs;

    /**
     * @param CachedLoader $loader
     * @param string       $dirs
     */
    public function __construct(CachedLoader $loader, $dirs)
    {
        $this->loader = $loader;
        $this->dirs = $dirs;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Bag $bag)
    {
        foreach ($this->dirs as $dir) {
            $bag = $this->loader->load(
                new DirResource(
                    $dir,
                    '*.php',
                    null,
                    [
                        'type'       => 'annotation',
                        'annotation' => '/^di\\\\/',
                    ]
                ),
                $bag
            );

            $bag = $this->loader->load(
                new DirResource(
                    $dir,
                    '*.php',
                    null,
                    [
                        'type'       => 'annotation',
                        'annotation' => '/^di\\\\aliases/',
                    ]
                ),
                $bag
            );
        }

        return $bag;
    }
}
