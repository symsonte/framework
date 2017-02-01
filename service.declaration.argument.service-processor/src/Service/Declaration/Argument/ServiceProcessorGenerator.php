<?php

namespace Symsonte\Service\Declaration\Argument;

use Symsonte\Service\Declaration;
use Symsonte\Service\Declaration\Storer;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 */
class ServiceProcessorGenerator
{
    /**
     * @param Storer $storer
     * @param string $class
     *
     * @return string
     */
    public function generate(Storer $storer, $class)
    {
        $methods = '';
        foreach ($storer->all() as $declaration) {
            $method = $this->generateService($declaration);
            $methods .=
<<<EOF
    {$method}

EOF;
        }

        return $this->generateLayout(
            $class,
            $methods
        );
    }

    /**
     * @param string $class
     * @param string $methods
     *
     * @return string
     */
    private function generateLayout($class, $methods)
    {
        return
<<<EOL
    class {$class}Processor
    {
        /**
         * @var Container
         */
        private \$container;

        /**
         * @param Container \$container
         */
        public function setContainer(\$container)
        {
            \$this->container = \$container;
        }
          
{$methods}
    }
EOL;
    }

    /**
     * @param Declaration $declaration
     *
     * @return string
     */
    private function generateService(Declaration $declaration)
    {
        $id = $declaration->getId();
        $name = sprintf('process_%s', $id);

        return
<<<EOL
    public function {$name}()
        {
            return \$this->container->get('$id');          
        }

EOL;
    }
}