<?php

namespace Symsonte\ServiceKit;

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
     *     installers: '#symsonte.service_kit.setup_install'
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
    public function install(Bag $bag)
    {
        foreach ($this->installers as $installer) {
            $bag = $installer->install($bag);
        }

        return $bag;
    }
}
