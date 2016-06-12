<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\Resource\FileResource;
use Symsonte\ServiceKit\Container\Builder as ContainerBuilder;
use Symsonte\ServiceKit\Resource\PerpetualCacher;
use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class PerpetualCachedServiceBuilder implements Builder
{
    /**
     * @var string
     */
    private $parametersFile;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var string[]
     */
    private $filters;

    /**
     * @param string   $parametersFile
     * @param string   $cacheDir
     * @param string[] $filters
     */
    public function __construct($parametersFile, $cacheDir, array $filters)
    {
        $this->parametersFile = $parametersFile;
        $this->cacheDir = $cacheDir;
        $this->filters = $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Bag $bag)
    {
        $setupBuilder = new SetupBuilder(
            $this->parametersFile,
            $this->cacheDir,
            $this->filters
        );
        $containerBuilder = new ContainerBuilder();
        $container = $containerBuilder->build($setupBuilder->build());
        /** @var PerpetualCacher $cacher */
        $cacher = $container->get('symsonte.service_kit.resource.perpetual_cacher');

        $resource = new FileResource(__FILE__);

        if ($cacher->approve($resource)) {
            return $cacher->retrieve($resource);
        }

        $serviceBuilder = new ServiceBuilder(
            $this->parametersFile,
            $this->cacheDir,
            $this->filters
        );

        $bag = $serviceBuilder->build($bag);

        $cacher->store($bag, $resource);

        return $bag;
    }
}
