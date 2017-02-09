<?php

namespace Symsonte\Resource;

use Composer\Autoload\ClassLoader;
use PhpParser\Comment\Doc;
use PhpParser\Error;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use PhpParser\ParserFactory;
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
     * {@inheritdoc}
     */
    public function parse($file, $name = null)
    {
        $data = [];

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

        try {
            $nodes = $parser->parse(file_get_contents($file));
            /* @var Namespace_ $namespace */
            if (isset($nodes[0])) {
                $namespace = $nodes[0];
                if ($namespace instanceof Namespace_) {
                    foreach ($namespace->stmts as $node) {
                        if ($node instanceof Class_) {
                            if ($namespace->name === null) {
                                // Ignore files like this symfony/symfony/src/Symfony/Component/ClassLoader/Tests/Fixtures/php5.4/traits.php
                                continue;
                            }

                            $className = sprintf(
                                '%s\\%s',
                                implode('\\', $namespace->name->parts),
                                $node->name
                            );

                            $doc = $node->getAttribute('comments', '');
                            if ($doc !== '') {
                                /** @var Doc $doc */
                                $doc = $doc[0];
                                $annotations = $this->resolveAnnotations($doc->getText());
                                foreach ($annotations as $annotation) {
                                    if (!$name || preg_match($name, $annotation['key'])) {
                                        $data['class'][] = [
                                            'class'    => $className,
                                            'key'      => $annotation['key'],
                                            'value'    => (array) Yaml::parse($annotation['value']),
                                            'metadata' => [
                                                'class' => $className,
                                            ],
                                        ];
                                    }
                                }
                            }

                            foreach ($node->stmts as $child) {
                                if ($child instanceof Property) {
                                    $doc = $child->getAttribute('comments', '');

                                    if ($doc !== '') {
                                        /** @var Doc $doc */
                                        $doc = $doc[0];

                                        $annotations = $this->resolveAnnotations($doc->getText());
                                        foreach ($annotations as $annotation) {
                                            if (!$name || preg_match($name, $annotation['key'])) {
                                                $data['properties'][] = [
                                                    'property' => $child->name,
                                                    'key'      => $annotation['key'],
                                                    'value'    => (array) Yaml::parse($annotation['value']),
                                                    'metadata' => [
                                                        'class' => $className,
                                                    ],
                                                ];
                                            }
                                        }
                                    }
                                } elseif ($child instanceof ClassMethod) {
                                    $doc = $child->getAttribute('comments', '');

                                    if ($doc !== '') {
                                        /** @var Doc $doc */
                                        $doc = $doc[0];

                                        $annotations = $this->resolveAnnotations($doc->getText());
                                        foreach ($annotations as $annotation) {
                                            if (!$name || preg_match($name, $annotation['key'])) {
                                                $data['method'][] = [
                                                    'method'   => $child->name,
                                                    'key'      => $annotation['key'],
                                                    'value'    => (array) Yaml::parse($annotation['value']),
                                                    'metadata' => [
                                                        'class' => $className,
                                                    ],
                                                ];
                                            }
                                        }
                                    }
                                } else {
                                    // It cloud be a class constant
                                    continue;
                                }
                            }
                        }
                    }
                } else {
                    // TODO: Implement case for class with no namespace
                    return $data;
                }
            } else {
                // Ignore files like this symfony/symfony/src/Symfony/Component/Routing/Tests/Fixtures/annotated.php
                return $data;
            }
        } catch (Error $e) {
            // Ignore files with bad syntax
        }

        return $data;
    }

    /**
     * Copied from Symfony/Component/Routing/Loader/AnnotationFileLoader.php.
     *
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
                } while ($i < $count && is_array($token) && in_array($token[0], [T_NS_SEPARATOR, T_STRING]));
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
     * @param string $comment
     *
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
     * Copied from phpDocumentor/ReflectionDocBlock/src/phpDocumentor/Reflection/DocBlock.php::cleanInput.
     *
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
        $comment = str_replace(["\r\n", "\r"], "\n", $comment);

        return $comment;
    }

    private function splitAnnotations($comment)
    {
        if (strpos($comment, "\n@")) {
            $comment = "\n".$comment;
            $comment = str_replace("\n@", "\n@@", $comment);
            $comment = explode("\n@", $comment);
            array_shift($comment);
        } else {
            $comment = [$comment];
        }

        return $comment;
    }

    private function cleanContents($annotations)
    {
        $data = [];
        foreach ($annotations as $annotation) {
            $data[] = str_replace("\n", '', $annotation);
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
