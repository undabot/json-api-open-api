<?php

declare(strict_types=1);

namespace JsonApiOpenApi\Helper;

use JsonApiOpenApi\Model\JsonApi\Schema\RelationshipSchema;

class RelationshipOpenApiGenerator
{
    public static $schemaCollection;

    public static function generateOpenApi(array $relationships): array
    {
        $result = [];
        foreach ($relationships as $relationship) {
            $result[] = self::generateSingleRelationshipOpenApi($relationship);
        }

        return $result;
    }

    public static function generateSingleRelationshipOpenApi(RelationshipSchema $relationship)
    {
        return [

        ];
    }
}
