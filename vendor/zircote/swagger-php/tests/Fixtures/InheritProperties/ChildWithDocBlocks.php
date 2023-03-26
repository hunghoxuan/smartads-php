<?php

namespace AnotherNamespace;

/**
 * @OA\Schema()
 */
class ChildWithDocBlocks extends \OpenApiFixtures\AncestorWithoutDocBlocks
{

    /**
     * @var bool
     * @OA\Property()
     */
    public $isBaby;
}
