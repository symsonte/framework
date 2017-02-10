<?php

namespace Symsonte\ServiceKit\Declaration\Bag;

use Symsonte\ServiceKit\Declaration\Bag;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     deductible: true
 * })
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
    )
    {
        $this->builders = $builders;
        $this->updaters = $updaters;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $bag = new Bag();

        foreach ($this->builders as $builder) {
            $bag = new Bag(
                array_merge(
                    $bag->getDeclarations(),
                    $builder->build()->getDeclarations()
                )
            );
        }

        $declarations = [];
        foreach ($bag->getDeclarations() as $declaration) {
            foreach ($this->updaters as $updater) {
                $declaration = $updater->update($declaration);
            }

            $declarations[] = $declaration;
        }

        return new Bag(
            $declarations,
            $bag->getParameters()
        );
    }
}
