<?php

namespace Symsonte\Resource;

use Composer\Autoload\ClassLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Yosmany Garcia <yosmanyga@gmail.com>
 *
 * @ds\service({
 *     private: true
 * })
 *
 * @di\service({
 *     private: true
 * })
 */
class YamlDocParser implements DocParser
{
    /**
     * @inheritdoc
     */
    public function parse($file, $name = null)
    {
        $data = [];
        $class = $this->findClass($file);

        if ($class !== false) {
            // TODO: Handle exception when a class can't be required because it needs a nonexistent interface or class
            if (strpos($class, 'Symfony') !== false
                || strpos($class, 'Foo') !== false
                || strpos($class, 'Bar') !== false
                || strpos($class, 'Test') !== false
                || strpos($class, 'Aura\Cli\_Config') !== false
            ) {
                return $data;
            }

            $ref = new \ReflectionClass($class);

            $annotations = $this->resolveAnnotations($ref->getDocComment());
            foreach ($annotations as $annotation) {
                if (!$name || preg_match($name, $annotation['key'])) {
                    $data['class'][] = array(
                        'class' => $class,
                        'key' => $annotation['key'],
                        'value' => (array) Yaml::parse($annotation['value']),
                        'metadata' => array(
                            'class' => $class
                        )
                    );
                }
            }

            foreach ($ref->getProperties() as $property) {
                $annotations = $this->resolveAnnotations($property->getDocComment());
                foreach ($annotations as $annotation) {
                    if (!$name || preg_match($name, $annotation['key'])) {
                        $data['properties'][] = array(
                            'property' => $property->getName(),
                            'key' => $annotation['key'],
                            'value' => (array) Yaml::parse($annotation['value']),
                            'metadata' => array(
                                'class' => $class
                            )
                        );
                    }
                }
            }

            foreach ($ref->getMethods() as $method) {
                $annotations = $this->resolveAnnotations($method->getDocComment());
                foreach ($annotations as $annotation) {
                    if (!$name || preg_match($name, $annotation['key'])) {
                        $data['method'][] = array(
                            'method' => $method->getName(),
                            'key' => $annotation['key'],
                            'value' => (array) Yaml::parse($annotation['value']),
                            'metadata' => array(
                                'class' => $class
                            )
                        );
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Copied from Symfony/Component/Routing/Loader/AnnotationFileLoader.php
     * @author Fabien Potencier <fabien@symfony.com>
     *
     * Returns the full class name for the first class in the file.
     *
     * @param string $file A PHP file path
     * @codeCoverageIgnore
     *
     * @return string|false Full class name if found, false otherwise
     */
    private function findClass($file)
    {
        $class = false;
        $namespace = false;
        $tokens = token_get_all(file_get_contents($file));
        for ($i = 0, $count = count($tokens); $i < $count; $i++) {
            $token = $tokens[$i];

            if (!is_array($token)) {
                continue;
            }

            if (true === $class && T_STRING === $token[0]) {
                return $namespace.'\\'.$token[1];
            }

            if (true === $namespace && T_STRING === $token[0]) {
                $namespace = '';
                do {
                    $namespace .= $token[1];
                    $token = $tokens[++$i];
                } while ($i < $count && is_array($token) && in_array($token[0], array(T_NS_SEPARATOR, T_STRING)));
            }

            if (T_CLASS === $token[0]) {
                $class = true;
            }

            if (T_NAMESPACE === $token[0]) {
                $namespace = true;
            }
        }

        return false;
    }

    /**
     * @param  string $comment
     * @return array
     */
    private function resolveAnnotations($comment)
    {
        if (!$comment) {
            return [];
        }

        $comment = $this->cleanAnnotations($comment);
        $annotations = $this->splitAnnotations($comment);
        $annotations = $this->cleanContents($annotations);
        $annotations = $this->parseAnnotations($annotations);

        return $annotations;
    }

    /**
     * Copied from phpDocumentor/ReflectionDocBlock/src/phpDocumentor/Reflection/DocBlock.php::cleanInput
     * @codeCoverageIgnore
     *
     * @param string $comment
     *
     * @return string
     */
    private function cleanAnnotations($comment)
    {
        $comment = trim(
            preg_replace(
                '#[ \t]*(?:\/\*\*|\*\/|\*)?[ \t]{0,1}(.*)?#u',
                '$1',
                $comment
            )
        );

        // reg ex above is not able to remove */ from a single line docblock
        if (substr($comment, -2) == '*/') {
            $comment = trim(substr($comment, 0, -2));
        }

        // normalize strings
        $comment = str_replace(array("\r\n", "\r"), "\n", $comment);

        return $comment;
    }

    private function splitAnnotations($comment)
    {
        if (strpos($comment, "\n@")) {
            $comment = "\n" . $comment;
            $comment = str_replace("\n@", "\n@@", $comment);
            $comment = explode("\n@", $comment);
            array_shift($comment);
        } else {
            $comment = array($comment);
        }

        return $comment;
    }

    private function cleanContents($annotations)
    {
        $data = [];
        foreach ($annotations as $annotation) {
            $data[] = str_replace("\n", "", $annotation);
        }

        return $data;
    }

    private function parseAnnotations($annotations)
    {
        $parsedAnnotations = [];
        foreach ($annotations as $annotation) {
            $key = substr(strstr($annotation, '(', true), 1);
            if ($key) {
                $parsedAnnotation['key'] = $key;
                $parsedAnnotation['value'] = substr(strstr($annotation, '('), 1, -1);
                $parsedAnnotations[] = $parsedAnnotation;
            } else {
                $key = substr($annotation, 1);
                if ($key) {
                    $parsedAnnotation['key'] = $key;
                    $parsedAnnotation['value'] = '';
                    $parsedAnnotations[] = $parsedAnnotation;
                }
            }
        }

        return $parsedAnnotations;
    }
}
