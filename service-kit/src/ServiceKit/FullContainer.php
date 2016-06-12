<?php

namespace Symsonte\ServiceKit;

use Symsonte\Resource\DelegatorFlatReader;
use Symsonte\Resource\YamlFileBuilder;
use Symsonte\Resource\YamlFileFlatReader;
use Symsonte\Service\Container;
use Symsonte\ServiceKit\Declaration\Bag;

class FullContainer implements Container
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param string   $parametersFile
     * @param string[] $filters
     */
    public function __construct($parametersFile, $filters = [])
    {
        $flatReader = new DelegatorFlatReader([
            new YamlFileFlatReader(),
        ]);

        $parameters = $flatReader->read(
            (new YamlFileBuilder())->build($parametersFile)
        );
        foreach ($parameters as $i => $parameter) {
            $parameters[$i] = str_replace('__DIR__', dirname($parametersFile), $parameter);
        }

        $bag = new Bag(
            [],
            $parameters
        );

        $composerInstaller = new ComposerInstaller($parameters['cache_dir']);

        $bag = $composerInstaller->install($bag, $filters);

        $this->container = (new ContainerBuilder())->build($bag);

//        $storer = new FilesystemStorer($cache);
//
//        if ($storer->has('container')) {
//            $this->container = $storer->get('container');
//
//            return;
//        }
//
//        $storer->add($this->container, 'container');
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->container->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return $this->container->get($id);
    }
}
