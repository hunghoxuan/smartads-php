<?php

namespace OpenApiFixtures;

class GrandAncestor
{

    /**
     * @OA\Property();
     * @var string
     */
    public $firstname;

    /**
     * @OA\Property(property="lastname");
     * @var string
     */
    public $lastname;
}
