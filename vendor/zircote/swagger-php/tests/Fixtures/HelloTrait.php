<?php

namespace OpenApiFixures;

/**
 * @OA\Schema(schema="trait")
 */
trait Hello
{

    /**
     * @OA\Property()
     */
    public $greet = 'Hello!';
}
