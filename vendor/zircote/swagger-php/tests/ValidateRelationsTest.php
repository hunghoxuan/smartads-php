<?php

/**
 * @license Apache 2.0
 */

namespace OpenApiTests;

/**
 * Test if the nesting/parent relations are coherent.
 */
class ValidateRelationsTest extends OpenApiTestCase
{

    /**
     *
     * @dataProvider getAnnotationClasses
     *
     * @param string $class
     */
    public function testAncestors($class)
    {
        foreach ($class::$_parents as $parent) {
            $found = false;
            foreach (array_keys($parent::$_nested) as $nested) {
                if ($nested === $class) {
                    $found = true;
                    break;
                }
            }
            if ($found === false) {
                $this->fail($class.' not found in '.$parent."::\$_nested. Found:\n  ".implode("\n  ", array_keys($parent::$_nested)));
            }
        }
    }

    /**
     *
     * @dataProvider getAnnotationClasses
     *
     * @param string $class
     */
    public function testNested($class)
    {
        foreach (array_keys($class::$_nested) as $nested) {
            $found = false;
            foreach ($nested::$_parents as $parent) {
                if ($parent === $class) {
                    $found = true;
                    break;
                }
            }
            if ($found === false) {
                $this->fail($class.' not found in '.$nested."::\$parent. Found:\n  ".implode("\n  ", $nested::$_parents));
            }
        }
    }

    /**
     * dataProvider for testExample
     *
     * @return array
     */
    public function getAnnotationClasses()
    {
        $classes = [];
        $dir = new \DirectoryIterator(__DIR__.'/../src/Annotations');
        foreach ($dir as $entry) {
            if ($entry->getFilename() === 'AbstractAnnotation.php') {
                continue;
            }
            if ($entry->getExtension() === 'php') {
                $classes[] = ['OpenApi\Annotations\\'.substr($entry->getFilename(), 0, -4)];
            }
        }
        return $classes;
    }
}
