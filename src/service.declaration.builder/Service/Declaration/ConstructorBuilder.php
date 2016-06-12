<?php

namespace Symsonte\Service\Declaration;

use Symsonte\Service\ConstructorDeclaration;
use Symsonte\Service\Declaration\Call\Storer as CallStorer;
use Symsonte\Service\Declaration\Storer as DeclarationStorer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ConstructorBuilder
{
    /**
     * @var DeclarationStorer
     */
    private $declarationStorer;

    /**
     * @var IdStorer
     */
    private $privateStorer;

    /**
     * @var IdStorer
     */
    private $disposableStorer;

    /**
     * @var TagStorer
     */
    private $tagStorer;

    /**
     * @var CallStorer
     */
    private $circularCallStorer;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $method;

    /**
     * @param DeclarationStorer|null $declarationStorer
     * @param IdStorer|null          $privateStorer
     * @param TagStorer|null         $tagStorer
     * @param CallStorer|null        $circularCallStorer
     */
    public function __construct(
        DeclarationStorer $declarationStorer = null,
        IdStorer $privateStorer = null,
        TagStorer $tagStorer = null,
        CallStorer $circularCallStorer = null
    ) {
        $this->declarationStorer = $declarationStorer ?: new DeclarationStorer();
        $this->privateStorer = $privateStorer ?: new IdStorer();
        $this->tagStorer = $tagStorer ?: new TagStorer();
        $this->circularCallStorer = $circularCallStorer ?: new CallStorer();
    }

    /**
     * @return DeclarationStorer
     */
    public function getDeclarationStorer()
    {
        return $this->declarationStorer;
    }

    /**
     * @return IdStorer
     */
    public function getPrivateStorer()
    {
        return $this->privateStorer;
    }

    /**
     * @return TagStorer
     */
    public function getTagStorer()
    {
        return $this->tagStorer;
    }

    /**
     * @return CallStorer
     */
    public function getCircularCallStorer()
    {
        return $this->circularCallStorer;
    }

    /**
     * @param string      $class
     * @param string|null $id
     *
     * @return $this
     */
    public function add($class, $id = null)
    {
        $this->id = $id ?: $this->generateName($class);

        $this->declarationStorer->add(
            new ConstructorDeclaration($this->id, $class)
        );

        return $this;
    }

    /**
     * @param string $argument
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function theServiceHasAServiceArgument($argument)
    {
        if (!isset($this->id)) {
            throw new \Exception('You need to add the service first.');
        }

        $this->declarationStorer->get($this->id)->appendArgument(new ServiceArgument($argument));

        return $this;
    }

    /**
     * @param string $argument
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function theServiceHasAParameterArgument($argument)
    {
        if (!isset($this->id)) {
            throw new \Exception('You need to add the service first.');
        }

        $this->declarationStorer->get($this->id)->appendArgument(new ParameterArgument($argument));

        return $this;
    }

    /**
     * @param string $argument
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function theServiceHasATaggedServicesArgument($argument)
    {
        if (!isset($this->id)) {
            throw new \Exception('You need to add the service first.');
        }

        $this->declarationStorer->get($this->id)->appendArgument(new TaggedServicesArgument($argument));

        return $this;
    }

    /**
     * @throws \Exception
     *
     * @return $this
     */
    public function theServiceIsPrivate()
    {
        if (!isset($this->id)) {
            throw new \Exception('You need to add the service first.');
        }

        $this->privateStorer->add($this->id);

        return $this;
    }

    /**
     * @throws \Exception
     *
     * @return $this
     */
    public function theServiceIsDisposable()
    {
        if (!isset($this->id)) {
            throw new \Exception('You need to add the service first.');
        }

        $this->disposableStorer->add($this->id);

        return $this;
    }

    /**
     * @param $tag
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function theServiceHasATag($tag)
    {
        if (!isset($this->id)) {
            throw new \Exception('You need to add the service first.');
        }

        $this->tagStorer->add($this->id, $tag);

        return $this;
    }

    /**
     * @param string $method
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function theServiceHasCircularCall($method)
    {
        if (!isset($this->id)) {
            throw new \Exception('You need to add the service first.');
        }

        $this->method = $method;

        $this->circularCallStorer->add(
            $this->id,
            new Call($method)
        );

        return $this;
    }

    /**
     * @param string $argument
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function theCallHasServiceArgument($argument)
    {
        if (!isset($this->method)) {
            throw new \Exception();
        }

        $call = $this->circularCallStorer->get($this->id, $this->method);

        $this->circularCallStorer->add(
            $this->id,
            new Call(
                $this->method,
                array_merge(
                    $call->getArguments(),
                    [new ServiceArgument($argument)]
                )
            )
        );

        return $this;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function generateName($class)
    {
        return
            strtolower(
                strtr(
                    preg_replace('/(?<=[a-zA-Z0-9])[A-Z]/', '_\\0', $class),
                    '\\',
                    '.'
                )
            );
    }
}
