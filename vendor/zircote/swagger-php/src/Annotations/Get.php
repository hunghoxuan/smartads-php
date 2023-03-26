<?php

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

/**
 * @Annotation
 */
class Get extends Operation
{
    /**
     * @inheritdoc
     */
    public $method = 'get';

    /**
     * @inheritdoc
     */
    public static $_parents = [
        'OpenApi\Annotations\PathItem'
    ];
}