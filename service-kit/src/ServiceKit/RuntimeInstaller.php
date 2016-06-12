<?php

namespace Symsonte\ServiceKit;

use Symsonte\ServiceKit\Declaration\Bag;
use Symsonte\ServiceKit\Resource\Loader;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class RuntimeInstaller implements Installer
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
     * @param Bag $bag
     *
     * @return Bag
     */
    public function install(Bag $bag)
    {
        foreach ($this->dirs as $dir) {
            $bag = $this->loader->load(
                [
                    'dir'    => $dir,
                    'filter' => '*.php',
                    'extra'  => [
                        'type'       => 'annotation',
                        'annotation' => '/^di\\\\/',
                    ],
                ],
                $bag
            );
        }

        return $bag;
    }
}
