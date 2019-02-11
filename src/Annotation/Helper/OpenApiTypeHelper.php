<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Annotation\Helper;

class OpenApiTypeHelper
{
    private static $map = [
        'bool' => 'boolean',
        'int' => 'integer',
    ];

    public static function getOpenApiType(string $type): string
    {
        return self::$map[$type] ?? $type;
    }
}
