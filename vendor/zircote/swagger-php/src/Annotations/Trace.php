<?php

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

/**
 * @Annotation
 */
class Trace extends Operation
{
    /**
     * @inheritdoc
     */
    public $method = 'trace';

    /**
     * @inheritdoc
     */
    public static $_parents = [
        'OpenApi\Annotations\PathItem'
    ];
}
