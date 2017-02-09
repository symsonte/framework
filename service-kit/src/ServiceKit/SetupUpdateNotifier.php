<?php

namespace Symsonte\ServiceKit;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service()
 */
class SetupUpdateNotifier
{
    /**
     * @var Updater[]
     */
    private $updaters;

    /**
     * @param Updater[]|null $updaters
     *
     * @ds\arguments({
     *     updaters: '#symsonte.service_kit.setup_update'
     * })
     */
    public function __construct(array $updaters = null)
    {
        $this->updaters = $updaters ?: [];
    }

    /**
     * @param Declaration $declaration
     *
     * @return Declaration
     */
    public function update(Declaration $declaration)
    {
        foreach ($this->updaters as $initializer) {
            $declaration = $initializer->update($declaration);
        }

        return $declaration;
    }
}