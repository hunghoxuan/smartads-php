<?php

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

/**
 * @Annotation
 */
class Put extends Operation
{
    /**
     * @inheritdoc
     */
    public $method = 'put';

    /**
     * @inheritdoc
     */
    public static $_parents = [
        'OpenApi\Annotations\PathItem'
    ];
}
