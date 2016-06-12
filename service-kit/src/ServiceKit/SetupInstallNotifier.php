<?php

namespace Symsonte\ServiceKit;

use Symsonte\ServiceKit;
use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service()
 */
class SetupInstallNotifier
{
    /**
     * @var Installer[]
     */
    private $installers;

    /**
     * @param Installer[]|null $installers
     *
     * @ds\arguments({
     *     connector: '#symsonte.service_kit.setup_install'
     * })
     */
    public function __construct(array $installers = null)
    {
        $this->installers = $installers ?: [];
    }

    /**
     * @param Bag $bag
     *
     * @return Bag
     */
    public function start(Bag $bag)
    {
        foreach ($this->installers as $initializer) {
            $bag = $initializer->install($bag);
        }

        return $bag;
    }
}