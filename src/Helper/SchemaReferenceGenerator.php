<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Helper;

class SchemaReferenceGenerator
{
    public static function ref(string $referenceId): string
    {
        return '#/components/schemas/' . $referenceId;
    }
}
