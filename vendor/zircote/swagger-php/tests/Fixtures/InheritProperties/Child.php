<?php

namespace AnotherNamespace;

/**
 * @OA\Schema()
 */
class Child extends \OpenApiFixtures\Ancestor
{

    /**
     * @var bool
     * @OA\Property()
     */
    public $isBaby;
}
