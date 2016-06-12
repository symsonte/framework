<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service()
 */
class EventBuilder implements Builder
{
    /**
     * @var Builder[]
     */
    private $builders;

    /**
     * @var Updater[]
     */
    private $updaters;

    /**
     * @param Builder[] $builders
     * @param Updater[] $updaters
     *
     * @ds\arguments({
     *     builders: '#symsonte.service_kit.declaration.bag.builder',
     *     updaters: '#symsonte.service_kit.declaration.bag.updater'
     * })
     */
    public function __construct(
        array $builders,
        array $updaters
    ) {
        $this->builders = $builders;
        $this->updaters = $updaters;
    }

    /**
     * {@inheritdoc}
     */
    public function build(Bag $bag)
    {
        foreach ($this->builders as $builder) {
            $bag = $builder->build($bag);
        }

        $declarations = [];
        foreach ($bag->getDeclarations() as $id => $declaration) {
            foreach ($this->updaters as $updater) {
                $declaration = $updater->update($declaration);
            }

            $declarations[$id] = $declaration;
        }

        return new Bag(
            $declarations,
            $bag->getParameters()
        );
    }
}
