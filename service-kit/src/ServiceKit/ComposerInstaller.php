<?php

namespace Symsonte\ServiceKit;

use Composer\Autoload\ClassLoader;
use Composer\Json\JsonFile;
use Composer\Package\CompletePackage;
use Composer\Repository\InstalledFilesystemRepository;
use Symsonte\Resource\Cacher;
use Symsonte\Resource\FileResource;
use Symsonte\Service\Declaration\ScalarArgument;
use Symsonte\Service\Declaration\ServiceArgument;
use Symsonte\ServiceKit\Declaration\Bag;
use Symsonte\Service\ConstructorDeclaration;
use Symsonte\ServiceKit\Resource\Loader;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ComposerInstaller
{
    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var Cacher
     */
    private $cacher;

    /**
     * @param Loader $loader
     * @param Cacher $cacher
     */
    public function __construct(
        Loader $loader,
        Cacher $cacher
    )
    {
        $this->loader = $loader;
        $this->cacher = $cacher;
    }

    /**
     * @param Bag    $bag
     *
     * @return Bag
     */
    public function install(Bag $bag)
    {
        $resource = new FileResource(sprintf("%s/../../../../../../vendor/composer/installed.json", __DIR__));

        if ($this->cacher->approve($resource)) {
            return $this->cacher->retrieve($resource);
        }

        /** @var ClassLoader $loader */
        $loader = include(sprintf("%s/../../../../../../vendor/autoload.php", __DIR__));

        foreach (array_merge($loader->getPrefixes(), $loader->getPrefixesPsr4()) as $dirs) {
            $internalBag = new Bag();
            foreach ($dirs as $dir) {
                $internalBag->merge($this->loader->load([
                    'dir' => $dir,
                    'filter' => '*.php',
                    'extra' => [
                        'type' => 'annotation',
                        'annotation' => '/^ds\\\\/'
                    ]
                ]));
            }

            if (!empty($internalBag->getDeclarations())) {
                $bag->merge($internalBag);
            } else {
                $bag->merge(new Bag(
                    [
                        new Declaration(
                            new ConstructorDeclaration(
                                uniqid(),
                                'Symsonte\ServiceKit\RuntimeInstaller',
                                [
                                    new ServiceArgument('symsonte.service_kit.resource.loader'),
                                    new ScalarArgument($dirs)
                                ]
                            ),
                            false,
                            true,
                            false,
                            ['symsonte.service_kit.setup_install'],
                            []
                        )
                    ]
                ));
            }
        }

        $this->cacher->store($bag, $resource);

        return $bag;
    }
}
