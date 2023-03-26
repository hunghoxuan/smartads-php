<?php

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

/**
 * @Annotation
 */
class Delete extends Operation
{
    /**
     * @inheritdoc
     */
    public $method = 'delete';

    /**
     * @inheritdoc
     */
    public static $_parents = [
        'OpenApi\Annotations\PathItem'
    ];
}
