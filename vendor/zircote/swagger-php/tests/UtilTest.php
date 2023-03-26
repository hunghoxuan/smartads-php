<?php

/**
 * @license Apache 2.0
 */

namespace OpenApiTests;

class UtilTest extends OpenApiTestCase
{
    public function testExclude()
    {
        $openapi = \OpenApi\scan(__DIR__.'/Fixtures', ['exclude' => ['Customer.php', 'UsingRefs.php', 'UsingPhpDoc.php', 'DynamicReference.php', 'GrandAncestor.php']]);
        $this->assertSame('Fixture for ParserTest', $openapi->info->title, 'No errors about duplicate @OA\Info() annotations');
    }
}